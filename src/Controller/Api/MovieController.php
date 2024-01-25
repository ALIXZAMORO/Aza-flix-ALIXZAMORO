<?php

namespace App\Controller\Api;

use App\Entity\Movie;
use App\Repository\MovieRepository;
use Doctrine\ORM\EntityNotFoundException;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/api/movies", name="app_api_movie_")
 */
class MovieController extends CoreApiController
{
    /**
     * list all movies
     * 
     * @Route("",name="browse", methods={"GET"})
     *
     * @param MovieRepository $movieRepository
     * @return JsonResponse
     */
    public function browse(MovieRepository $movieRepository): JsonResponse
    {
        $allMovies = $movieRepository->findAll();
        return $this->json200($allMovies, ["movie_browse"]);
    }
    /**
     * @Route("/{id}", name="read", requirements={"id"="\d+"}, methods={"GET"})
     */
    // public function read(?Movie $movie,MovieRepository $movieRepository): JsonResponse
    public function read($id,MovieRepository $movieRepository): JsonResponse
    {
        $movie = $movieRepository->find($id);
        // gestion 404
        if ($movie === null){
            // ! on est dans une API donc pas de HTML
            // throw $this->createNotFoundException();
            return $this->json(
                // on pense UX : on fournit un message
                [
                    "message" => "Ce film n'existe pas"
                ],
                // le code de status : 404
                Response::HTTP_NOT_FOUND
                // on a pas besoin de preciser les autres arguments
            );
        }
        return $this->json($movie, 200, [], 
            [
                "groups" => 
                [
                    "movie_read"
                ]
            ]);
    }

    /**
     * ajout de film
     *
     * @Route("",name="add", methods={"POST"})
     * 
     * @param Request $request
     * @param SerializerInterface $serializer
     * @param MovieRepository $movieRepository
     * 
     * @ IsGranted("ROLE_ADMIN")
     */
    public function add(
        Request $request, 
        SerializerInterface $serializer, 
        MovieRepository $movieRepository,
        ValidatorInterface $validatorInterface)
    {
        // Récupérer le contenu JSON
        $jsonContent = $request->getContent();
        
        // Désérialiser (convertir) le JSON en entité Doctrine Movie
        try { // on tente de désérialiser
            $movie = $serializer->deserialize($jsonContent, Movie::class, 'json');
        } catch (EntityNotFoundException $e){
            // spécial pour DoctrineDenormalizer
            return $this->json("Denormalisation : ". $e->getMessage(), Response::HTTP_BAD_REQUEST);
        } catch (Exception $exception){
            // Si on n'y arrive pas, on passe ici
            // dd($exception);
            // code 400 ou 422
            return $this->json("JSON Invalide : " . $exception->getMessage(), Response::HTTP_BAD_REQUEST);
        }
        
        // on valide les données de notre entité
        // ? https://symfony.com/doc/5.4/validation.html#using-the-validator-service
        $errors = $validatorInterface->validate($movie);
        // Y'a-t-il des erreurs ?
        if (count($errors) > 0) {
            // TODO Retourner des erreurs de validation propres
            return $this->json($errors, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        // On sauvegarde l'entité
        $movieRepository->add($movie, true);

        return $this->json($movie, Response::HTTP_CREATED, [], ["groups"=>["movie_read"]]);
    }
}
