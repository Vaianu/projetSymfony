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
use App\Repository\EnchereRepository;
use App\Repository\HistoriqueEncheresRepository;
use App\Repository\AchatRepository;


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
    public function historiqueEncheres(HistoriqueEncheresRepository $historiqueEncheresRepository): Response
    {
		$user = $this->getUser();
		$achats = $user->getAchats();
		$nbJetons = 0;
			
		foreach($achats as $achat)
		{
			$nbJetons += $achat->getPackjetons()->getNbjetons();
		}
		
		$nbJetons -= count($user->getHistoriqueEncheres());
		
		$historiqueEncheres = $historiqueEncheresRepository->findBy(
			['utilisateur' => $user],
			['date_enchere' => 'DESC']
		);
			
		return $this->render('utilisateur/historiqueEncheres.html.twig', [
		'historiqueEncheres' => $historiqueEncheres,
		'nbJetons' => $nbJetons,
		]);
    }
	
	/**
     * @Route("/utilisateur/mes-achats", name="utilisateur_achats", methods={"GET"})
     */
    public function achats(AchatRepository $achatRepository): Response
    {
		$user = $this->getUser();
		$achats = $user->getAchats();
		$nbJetons = 0;
			
		foreach($achats as $achat)
		{
			$nbJetons += $achat->getPackjetons()->getNbjetons();
		}
		
		$nbJetons -= count($user->getHistoriqueEncheres());
		
		$achats = $achatRepository->findBy(
			['utilisateur' => $user],
			['date_achat' => 'DESC']
		);
		
		return $this->render('utilisateur/achats.html.twig', [
		'achats' => $achats,
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
			
			date_default_timezone_set('Europe/Paris');
			$date_heure_actuelle = new \DateTime('now');
			$enchere = $enchereRepository->find($request->request->get('id_enchere'));
			if($nbJetons > count($user->getHistoriqueEncheres()) && 
				$date_heure_actuelle >= $enchere->getDateDebut() && $date_heure_actuelle < $enchere->getDateFin()) // on vérifie si l'enchère est actuellement en cours
			{
				$historiqueEncheres = new HistoriqueEncheres();
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
