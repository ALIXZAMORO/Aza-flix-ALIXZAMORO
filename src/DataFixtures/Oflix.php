<?php

namespace App\DataFixtures;

use App\Entity\Genre;
use App\Entity\Movie;
use App\Entity\Person;
use App\Entity\Type;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class Oflix extends Fixture
{
    /**
     * Création de donnée
     *
     * @param ObjectManager $manager
     * @return void
     */
    public function load(ObjectManager $manager): void
    {
     
        $genres = ["Action", "Animation", "Aventure", "Comédie", "Dessin Animé", "Documentaire", "Drame", "Espionnage", "Famille", "Fantastique", "Historique", "Policier", "Romance", "Science-fiction", "Thriller", "Western"];
        

        $allGenre = [];
        foreach ($genres as $genreName) {
            
            $newGenre = new Genre();

            $newGenre->setName($genreName);

            $manager->persist($newGenre);

            $allGenre[] = $newGenre;
        }

        $types = ["film", "série"];
        $allTypes = [];
        foreach ($types as $type) {
            
            $newType = new Type();

            $newType->setName($type);

            $manager->persist($newType);

            $allTypes[] = $newType;
        }

        $newPerson = [];
        for ($i=0; $i < 20; $i++) { 
      
            $newPerson = new Person();

            $newPerson->setFirstname("prénom #" .$i);
            $newPerson->setLastname("nom #" .$i);

            $manager->persist($newPerson);

            $allPerson[] = $newPerson; 
        }

        for ($i=0; $i < 100; $i++) { 
                
            $newMovie = new Movie();

            $newMovie->setTitle("Titre #" .$i);
            $newMovie->setDuration(mt_rand(10, 360));
            $newMovie->setRating(mt_rand(0,50) / 10);
            $newMovie->setSummary("lorem ipsum summary");
            $newMovie->setSynopsis("lorem ipsum synopsis");
            $newMovie->setReleaseDate(new DateTime("1970-01-01"));
            $newMovie->setCountry("FR");
            $newMovie->setPoster("https://img.freepik.com/vecteurs-premium/fond-film-cinema-premiere_41737-251.jpg");

            $randomType = $allTypes[mt_rand(0, count($allTypes)-1)];
            $newMovie->setType($randomType);

            $manager->persist($newMovie);
        }

        $manager->flush();
    }
}
