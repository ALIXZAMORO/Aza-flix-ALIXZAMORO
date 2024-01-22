<?php

namespace App\Controller\Front;

use App\Entity\Review;
use App\Form\ReviewType;
use App\Repository\MovieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReviewController extends AbstractController
{
    /**
     * @Route("/movies/{id}/review/add", name="app_review_add", requirements={"id": "\d+"})
     */
    public function index($id, MovieRepository $movieRepository, Request $request, EntityManagerInterface $entityManagerInterface): Response
    {
        $movie = $movieRepository->find($id);

        if ($movie === null) { throw $this->createNotFoundException("ce film n'existe pas");}

        $newReview = new Review();
        $form = $this->createForm(ReviewType::class, $newReview);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            //dd($newReview);
            $newReview->setMovie($movie);

            $entityManagerInterface->persist($newReview);
            $entityManagerInterface->flush();

            return $this->redirectToRoute("app_front_movie_show", ["id"=> $movie->getId()]);

            }
        

        return $this->renderForm('front/review/index.html.twig', [

            "movieFromBDD" => $movie,
            "formulaire" => $form
            
        ]);
    }
}
