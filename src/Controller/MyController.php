<?php

namespace App\Controller;

use App\Entity\Achat;
use App\Entity\HistoriqueEncheres;
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
	
	/**
     * @Route("/utilisateur/{idEnchere}/placer", name="utilisateur_placer", methods={"GET","POST"})
     */
    public function placerOffre(Request $request, EnchereRepository $enchereRepository): Response
    {
		$historiqueEncheres = new HistoriqueEncheres();
        $form = $this->createForm(HistoriqueEncheresType::class, $historiqueEncheres);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
			$this->getUser()->addHistoriqueEnchere($historiqueEncheres);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($historiqueEncheres);
            $entityManager->flush();
		
			return $this->render('placer.html.twig', [
            'form' => $form->createView(),
			'nameButon' => "Placer",
			'messageConfirmation' => "Votre offre de ".strval($historiqueEncheres->getPrix())."€ a bien été placé sur cette enchère",
        ]);
        }

        return $this->render('placer.html.twig', [
            'form' => $form->createView(),
			'nameButon' => "Placer",
			'messageConfirmation' => null,
        ]);
    }

}
