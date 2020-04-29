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
		return $this->render('sans_connexion/index.html.twig', []);
    }
	
	/**
     * @Route("/encheres", name="encheres", methods={"GET"})
     */
    public function encheres(EnchereRepository $enchereRepository): Response
    {
		return $this->render('sans_connexion/encheres.html.twig', [
			'encheres' => $enchereRepository->findAll(),
		]);
    }
	
	/**
     * @Route("/utilisateur/placer", name="utilisateur_placer", methods={"GET","POST"})
     */
    public function placerOffre(EnchereRepository $enchereRepository): Response
    {	
		if ($this->getUser() && isset($_POST['prix_mise']) && isset($_POST['id_enchere']) && !empty($_POST['prix_mise'])) {
				$historiqueEncheres = new HistoriqueEncheres();
				$enchere = $enchereRepository->find($_POST['id_enchere']);
				$historiqueEncheres->setEnchere($enchere);
				$historiqueEncheres->setPrix($_POST['prix_mise']);
				$this->getUser()->addHistoriqueEnchere($historiqueEncheres);
				$entityManager = $this->getDoctrine()->getManager();
				$entityManager->persist($historiqueEncheres);
				$entityManager->flush();
        }
		else
		{
			return $this->redirectToRoute('app_login');
		}
		
		return $this->redirectToRoute('encheres');
    }

    /**
     * @Route("/utilisateur/acheter", name="utilisateur_acheter", methods={"GET","POST"})
     */
    public function acheterPackJetons(Request $request): Response
    {
		if($this->getUser())
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
		else
		{
			return $this->redirectToRoute('app_login');
		}
    }
	
}
