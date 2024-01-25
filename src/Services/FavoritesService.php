<?php

namespace App\Services;

use App\Entity\Movie;
use Exception;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class FavoritesService
{
    
    /**
     * le requete en cours
     *
     * @var RequestStack
     */
    private $request;

    // TODO : de qui/quoi a-t-on besoin ? des quels services ? de quel classe ?
    // 1. la session : que l'on trouve dans RequestStack (trouvé via la commande debug:autowiring)
    // les besoins en terme de services, donc injection de dépendance
    // doivent être fait au niveau du constructeur
    public function __construct(RequestStack $request)
    {
        $this->request = $request;
        // ! Accessing the session in the constructor is *NOT* recommended, since
        // it might not be accessible yet or lead to unwanted side-effects
        // $this->session = $requestStack->getSession();
        // ? a but de débug pour voir comment est fait la création de notre service
        // throw new Exception();
    }

    /**
    * get current session
    */
    public function getSession()
    {
        return $this->request->getSession();
    }

    // TODO : quels sont les besoins/fonctionnalités que l'on délègue a ce service
    // 1. list
    // 2. add
    // 3. remove
    // ? ce sont les méthodes qui seront appelées par nos controller
    // il ne faut pas faire d'injection de dépendance dans ces méthodes
    
    public function list()
    {
        $favoris = $this->getSession()->get("favoris", []);

        return $favoris;
    }

    public function add(Movie $movie)
    {
        // TODO améliorer avec un tableau
        // je récupère ce qu'il y a déjà en session
        $favorisList = $this->getSession()->get("favoris", []);
        // j'ajoute le nouveau film à un emplacement précis : son ID
        $favorisList[$movie->getId()] = $movie;

        // je met à jour la session avec la nouvelle liste
        $this->getSession()->set("favoris", $favorisList);
    }

    public function remove(Movie $movie)
    {
        // TODO : supprimer un favoris
        // 1. il me faut un id, parce que l'on pense au futur et la gestion de multiple favoris
        // 2. il me faut la session pour récupérer les favoris

        $favorisList = $this->getSession()->get("favoris", []);

        if (array_key_exists($movie->getId(), $favorisList)){
            // ? https://www.php.net/manual/en/function.unset.php
            unset($favorisList[$movie->getId()]);
            // met à jour la session
            $this->getSession()->set("favoris", $favorisList);
        }
    }

    public function removeAll()
    {
        // on met un tableau vide pour purger nos favoris
        $this->getSession()->set("favoris", []);
        // version plus bourine qui supprime directement la clé en session
        $this->getSession()->remove("favoris");
    }

}