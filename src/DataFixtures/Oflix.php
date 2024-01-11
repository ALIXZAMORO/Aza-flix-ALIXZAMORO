<?php

namespace App\DataFixtures;

use App\Entity\Casting;
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
        

        /** @var Genre[] $allGenre */
        $allGenre = [];
        foreach ($genres as $genreName) {
            
            $newGenre = new Genre();

            $newGenre->setName($genreName);

            $manager->persist($newGenre);

            $allGenre[] = $newGenre;
        }

        $types = ["film", "série"];
        /** @var Type[] $allTypes */
        $allTypes = [];
        foreach ($types as $type) {
            
            $newType = new Type();

            $newType->setName($type);

            $manager->persist($newType);

            $allTypes[] = $newType;
        }

        /** @var Person[] $allPerson */
        $allPerson = [];
        for ($i=0; $i < 2000; $i++) { 
      
            $newPerson = new Person();

            $newPerson->setFirstname("prénom #" .$i);
            $newPerson->setLastname("nom #" .$i);

            $manager->persist($newPerson);

            $allPerson[] = $newPerson; 
        }

        /** @var Movie[] $allMovies */
        $allMovies = [];
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

            $allMovies[] = $newMovie;
        }

        foreach ($allMovies as $movie) {
            
            $randomNbCasting = mt_rand(3,5);
            for ($i=1; $i <= $randomNbCasting; $i++) { 
                
                $newCasting = new Casting();

                $newCasting->setRole("Role #" .$i);
                $newCasting->setCreditOrder($i);

                $newCasting->setMovie($movie);
                $randomPerson = $allPerson[mt_rand(0, count($allPerson)-1)];
                $newCasting->setPerson($randomPerson);

                $manager->persist($newCasting);
            }
        }

        $manager->flush();
    }
}
