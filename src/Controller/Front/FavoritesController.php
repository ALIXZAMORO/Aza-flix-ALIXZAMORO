<?php

namespace App\Controller\Front;

use App\Entity\Movie;
use App\Models\MovieModel;
use App\Repository\MovieRepository;
use App\Services\FavoritesService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FavoritesController extends AbstractController
{
    /**
     * afficher le(s) film favoris
     * 
     * @Route("/favorites", name="app_front_favorites")
     */
    public function index(FavoritesService $favoritesService): Response    
    {

        // TODO utiliser le service
        $favorisList = $favoritesService->list();

        return $this->render('front/favorites/index.html.twig', 
        [
            // je donne les films favoris à ma vue pour l'affichage
            "movies" => $favorisList
        ]);
    }

    /**
     * ajout un film en favoris
     *
     * @Route("/favorites/add/{id}", name="app_front_favorites_add", requirements={"id"="\d+"})
     * 
     * @return Response
     */
    public function add($id, MovieRepository $movieRepository, FavoritesService $favoritesService): Response
    {
        // TODO : j'ai besoin de l'identifiant du film à mettre en favoris
        // ? comment l'utilisateur me fournit l'ID ?
        // avec un paramètre de route : {id}
        // dd($id);

        // TODO : j'ai besoin des informations du film en question
        $movie = $movieRepository->find($id);

        if ($movie === null){ throw $this->createNotFoundException("ce film n'existe pas.");}

        // TODO utiliser le service
        $favoritesService->add($movie);

        // ? j'ai fini le traitement, je n'ai rien à afficher de particulier
        // je vais donc rediriger mon utilisateur vers l'affichage des favoris
        // càd vers une autre route
        // la méthode redirectToRoute() me fournit une Response
        // je renvois de suite cette response
        return $this->redirectToRoute('app_front_favorites');
    }   

    /**
     * supression d'un favoris
     * 
     * @Route("/favorites/remove/{id}", name="app_front_favorites_remove", requirements={"id"="\d+"})
     *
     * @return Response
     */
    public function remove($id, FavoritesService $favoritesService, MovieRepository $movieRepository):Response
    {
        $movie = $movieRepository->find($id);
        if ($movie === null){ throw $this->createNotFoundException("ce film n'existe pas.");}
        
        $favoritesService->remove($movie);

        // on redirige pour l'affichage
        return $this->redirectToRoute('app_front_favorites');
    }

    /**
     * supprime tout les favoris
     *
     * @Route("favorites/clear", name="app_front_favorites_clear")
     * 
     * @param FavoritesService $favoritesService
     * @return Response
     */
    public function removeAll(FavoritesService $favoritesService): Response
    {
        $favoritesService->removeAll();

        // on redirige pour l'affichage
        return $this->redirectToRoute('app_front_favorites');

    }
}
