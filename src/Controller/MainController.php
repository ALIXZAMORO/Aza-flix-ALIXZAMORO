<?php

namespace App\Controller;

use App\Models\MovieModel;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class MainController extends AbstractController
{
/**
 * @Route("/", name="default", methods={"GET", "POST"})
 * 
 * @return Response
 */
    public function home(Request $request): Response
    {
      $allMovies = MovieModel::getAllMovies();
     dump($allMovies);

     $session = $request->getSession();
     dump($session->get("favoris"));

      
      $twigResponse = $this->render("main/home.html.twig",
    [

      "movieList" => $allMovies

    ]);
        
        return $twigResponse;
  }


/**
 * affichage d'un film
 * 
 * @Route("/movies/{id}", name="movie_show", requirements={"id"="\d+"} ,methods={"GET"})
 * 
 * @return Response
 */

public function show($id): Response
 {
  $movie = MovieModel::getMovie($id);

  $twigResponse = $this->render("main/show.html.twig",
   [

    "movieId" => $id,
    "movieForTwig" => $movie

  ]);

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