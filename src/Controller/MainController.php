<?php

namespace App\Controller;

use App\Models\MovieModel;
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
      $allMovies = MovieModel::getAllMovies();
     // dd($allMovies);

      $demoData = "Bonjour du mardi matin.";
      $twigResponse = $this->render("main/home.html.twig",
    [
      "monTexteKilEstBo" => $demoData,
      "movieList" => $allMovies

    ]);
        
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

  Response::HTTP_NOT_FOUND;

  return $twigResponse;
 }

 /**
  * @Route("/search", name="movie_search")
  * 
  * @return Response
  */
 public function  list(): Response
 {
  return $this->render("main/list.html.twig");
 }

}