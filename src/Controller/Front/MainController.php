<?php

namespace App\Controller\Front;

use App\Models\MovieModel;
use App\Repository\CastingRepository;
use App\Repository\MovieRepository;
use App\Repository\ReviewRepository;
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
    public function home(Request $request, MovieRepository $movieRepository): Response
    {
     // $allMovies = MovieModel::getAllMovies();

     $allMovies = $movieRepository->findAll();

     dump($allMovies);

     $session = $request->getSession();
     dump($session->get("favoris"));

      
      $twigResponse = $this->render("front/main/home.html.twig",
    [

      "movieList" => $allMovies

    ]);
        
        return $twigResponse;
  }


/**
 * affichage des détails d'un film
 * 
 * @Route("/movies/{id}", name="app_front_movie_show", requirements={"id"="\d+"} ,methods={"GET"})
 * 
 * @return Response
 */

public function show($id, MovieRepository $movieRepository, CastingRepository $castingRepository, ReviewRepository $reviewRepository): Response
 {
  $movie = $movieRepository->find($id);
  //dd($movie);

  if ($movie === null) {
    throw $this->createNotFoundException("Ce film n'existe pas");
  }

    $allCastingFromMovie = $castingRepository->findBy(["movie" => $movie], ["creditOrder" => "ASC"]);
  
    dump($allCastingFromMovie);

    $allReviews = $reviewRepository->findBy(
      [
          "movie" => $movie
      ],
      [
          "rating" => "DESC"
      ]
  );


  $twigResponse = $this->render("front/main/show.html.twig",
   [

    "movieId" => $id,
    "movieForTwig" => $movie,
    "allCastingFromBDD" => $allCastingFromMovie,
    "allReviewFromBDD" => $allReviews

  ]);

  return $twigResponse;

 }


 /**
  * @Route("/search", name="app_front_movie_search")
  * 
  * @return Response
  */
 public function  list(): Response
 {
  return $this->render("front/main/list.html.twig");
 }

}