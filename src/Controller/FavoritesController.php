<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FavoritesController extends AbstractController
{
    /**
     * @Route("/favorites", name="app_favorites")
     */
    public function index(Request $request): Response
    {
        $session = $request->getSession();
        dump($session);

        $session->set('favoris', "Vive les Radium");
        dump($session);

        return $this->render('favorites/index.html.twig', [
            'controller_name' => 'FavoritesController',
        ]);
    }
}