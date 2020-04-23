<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Repository\UtilisateurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Achat;
use App\Form\AchatFormType;

class MyController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(UtilisateurRepository $utilisateurRepository): Response
    {
        /*$utilisateur = $utilisateurRepository->find($this->getUser()->getId());
		$nbJetons = 0;
		for($i=0;$i<count($utilisateur->getAchats());$i++)
			$nbJetons += $utilisateur->getAchats()[$i]->getPackJetons()->getNbjetons();*/
		return $this->render('index.html.twig', [
			//'nbjetons' => $nbJetons,
		]);
    }

    /**
     * @Route("/achat", name="utilisateur_achat", methods={"GET","POST"})
     */
    public function achat(Request $request): Response
    {
		$achat = new Achat();
        $form = $this->createForm(AchatFormType::class, $achat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
			$this->getUser()->addAchat($achat);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($achat);
            $entityManager->flush();

            return $this->redirectToRoute('index');
        }

        return $this->render('achat.html.twig', [
            'form' => $form->createView(),
        ]);
    }

}
