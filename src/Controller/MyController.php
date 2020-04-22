<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MyController extends AbstractController
{	
	/**
     * @Route("/", name="index")
     */
    public function index(): Response
    {
        return $this->render('index.html.twig', []);
    }
	
} 