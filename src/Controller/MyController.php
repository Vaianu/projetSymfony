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
		if($user = $this->getUser())
		{
			$achats = $user->getAchats();
			$nbJetons = 0;
			
			foreach($achats as $achat)
			{
				$nbJetons += $achat->getPackjetons()->getNbjetons();
			}
			$nbJetons -= count($user->getHistoriqueEncheres());
			
			return $this->render('sans_connexion/index.html.twig', [
			'encheres' => $enchereRepository->findAll(),
			'nbJetons' => $nbJetons,
			]);
		}
		
		return $this->render('sans_connexion/index.html.twig', []);
    }
	
	/**
     * @Route("/encheres", name="encheres", methods={"GET"})
     */
    public function encheres(EnchereRepository $enchereRepository): Response
    {	
		if($user = $this->getUser())
		{
			$achats = $user->getAchats();
			$nbJetons = 0;
			
			foreach($achats as $achat)
			{
				$nbJetons += $achat->getPackjetons()->getNbjetons();
			}
			$nbJetons -= count($user->getHistoriqueEncheres());
			
			return $this->render('sans_connexion/encheres.html.twig', [
			'encheres' => $enchereRepository->findByEnchereAvailable(),
			'nbJetons' => $nbJetons,
			]);
		}
		
		
		
		return $this->render('sans_connexion/encheres.html.twig', [
			'encheres' => $enchereRepository->findByEnchereAvailable(),
		]);
    }
	
	/**
     * @Route("/utilisateur/mes-encheres", name="utilisateur_historiqueEncheres", methods={"GET"})
     */
    public function historiqueEncheres(): Response
    {
		$user = $this->getUser();
		$achats = $user->getAchats();
		$nbJetons = 0;
			
		foreach($achats as $achat)
		{
			$nbJetons += $achat->getPackjetons()->getNbjetons();
		}
		$nbJetons -= count($user->getHistoriqueEncheres());
			
		return $this->render('utilisateur/historiqueEncheres.html.twig', [
		'historiqueEncheres' => $this->getUser()->getHistoriqueEncheres(),
		'nbJetons' => $nbJetons,
		]);
    }
	
	/**
     * @Route("/utilisateur/mes-achats", name="utilisateur_achats", methods={"GET"})
     */
    public function achats(): Response
    {
		$user = $this->getUser();
		$achats = $user->getAchats();
		$nbJetons = 0;
			
		foreach($achats as $achat)
		{
			$nbJetons += $achat->getPackjetons()->getNbjetons();
		}
		$nbJetons -= count($user->getHistoriqueEncheres());
		
		return $this->render('utilisateur/achats.html.twig', [
		'achats' => $this->getUser()->getAchats(),
		'nbJetons' => $nbJetons,
		]);
    }
	
	/**
     * @Route("/utilisateur/placer", name="utilisateur_placer", methods={"GET","POST"})
     */
    public function placerOffre(Request $request,EnchereRepository $enchereRepository): Response
    {	
		if($request->request->get('prix_mise') && $request->request->get('id_enchere')) {
			$user = $this->getUser();
			$achats = $user->getAchats();
			$nbJetons = 0;
			foreach($achats as $achat)
			{
				$nbJetons += $achat->getPackjetons()->getNbjetons(); 
			}
			
			if($nbJetons > count($user->getHistoriqueEncheres()))
			{
				$historiqueEncheres = new HistoriqueEncheres();
				$enchere = $enchereRepository->find($request->request->get('id_enchere'));
				$historiqueEncheres->setEnchere($enchere);
				$historiqueEncheres->setPrix($request->request->get('prix_mise'));
				$user->addHistoriqueEnchere($historiqueEncheres);
				$entityManager = $this->getDoctrine()->getManager();
				$entityManager->persist($historiqueEncheres);
				$entityManager->flush();
			}
		}
		
		return $this->redirectToRoute('encheres');
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
				
			return $this->render('utilisateur/acheter.html.twig', [
			'form' => $form->createView(),
			'nameButon' => "Acheter",
			'messageConfirmation' => "Votre achat a bien été effectué",
			]);
		}
	
		return $this->render('utilisateur/acheter.html.twig', [
			'form' => $form->createView(),
			'nameButon' => "Acheter",
			'messageConfirmation' => null,
		]);
    }
	
}
