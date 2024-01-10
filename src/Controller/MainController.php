<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MainController extends AbstractController
{
/**
 * @Route("/", name="default", methods={"GET", "POST"})
 * 
 * @return Response
 */
    public function home(): Response
    {
      $twigResponse = $this->render("main/home.html.twig");
        
        return $twigResponse;
    }


/**
 * @Route("/movies/{id}", name="movie_show", requirements={"id"="\d+"} ,methods={"GET"})
 * 
 * @return Response
 */

public function show($id): Response
 {
  $twigResponse = $this->render("main/show.html.twig", [

    "movieId" => $id
    
  ]);

  return $twigResponse;

 }

}