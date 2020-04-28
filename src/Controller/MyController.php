<?php

namespace App\Controller;

use App\Entity\Achat;
use App\Entity\HistoriqueEncheres;
use App\Entity\Enchere;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\AchatFormType;
use App\Form\HistoriqueEncheresType;
use App\Repository\EnchereRepository;


class MyController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(EnchereRepository $enchereRepository): Response
    {
		return $this->render('index.html.twig', [
			'encheres' => $enchereRepository->findAll(),
			'idEnchereMessage' => null,
			'messageConfirmation' => null,
		]);
    }
	
	/**
     * @Route("/utilisateur/{id}/placer", name="utilisateur_placer", methods={"GET","POST"})
     */
    public function placerOffre(Request $request, Enchere $enchere, EnchereRepository $enchereRepository): Response
    {
        if (isset($_POST['prix_mise']) && $_POST['prix_mise'] > 0 && $_POST['prix_mise'] < 500) {
			$historiqueEncheres = new HistoriqueEncheres();
			$historiqueEncheres->setEnchere($enchere);
			$historiqueEncheres->setPrix($_POST['prix_mise']);
			$this->getUser()->addHistoriqueEnchere($historiqueEncheres);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($historiqueEncheres);
            $entityManager->flush();
		
			return $this->render('index.html.twig', [
			'encheres' => $enchereRepository->findAll(),
			'idEnchereMessage' => $enchere->getId(),
			'messageConfirmation' => "Votre mise de ".strval($historiqueEncheres->getPrix())." € a bien été placé sur cette enchère",
        ]);
        }

       return $this->render('index.html.twig', [
			'encheres' => $enchereRepository->findAll(),
			'idEnchereMessage' => null,
			'messageConfirmation' => null,
		]);
    }

    /**
     * @Route("/utilisateur/acheter", name="utilisateur_acheter", methods={"GET","POST"})
     */
    public function acheterPackJetons(Request $request): Response
    {
		$achat = new Achat();
        $form = $this->createForm(AchatFormType::class, $achat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
			$this->getUser()->addAchat($achat);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($achat);
            $entityManager->flush();
			
			return $this->render('acheter.html.twig', [
            'form' => $form->createView(),
			'nameButon' => "Acheter",
			'messageConfirmation' => "Votre achat a bien été effectué",
        ]);
        }

        return $this->render('acheter.html.twig', [
            'form' => $form->createView(),
			'nameButon' => "Acheter",
			'messageConfirmation' => null,
        ]);
    }
	
}
