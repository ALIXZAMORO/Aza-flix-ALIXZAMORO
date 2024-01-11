<?php

namespace App\Controller;

use App\Repository\MovieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReviewController extends AbstractController
{
    /**
     * @Route("/movies/{id}/review/add", name="app_review_add", requirements={"id": "\d+"})
     */
    public function index($id, MovieRepository $movieRepository): Response
    {
        $movie = $movieRepository->find($id);

        if ($movie === null) { throw $this->createNotFoundException("ce film n'existe pas");}

        return $this->render('review/index.html.twig', [

            "movieFromBDD" => $movie
            
        ]);
    }
}
