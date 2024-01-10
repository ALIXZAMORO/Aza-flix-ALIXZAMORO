<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MainController extends AbstractController
{
/**
 * @Route("/radium", name="default", methods={"GET", "POST"})
 * 
 * @return Response
 */
    public function home(): Response
    {
      $twigResponse = $this->render("main/home.html.twig");
        
        return $twigResponse;
    }
}