<?php

namespace App\DataFixtures;

use App\Entity\Casting;
use App\Entity\Genre;
use App\Entity\Movie;
use App\Entity\Person;
use App\Entity\Season;
use App\Entity\Type;
use App\Entity\User;
use App\Services\OmdbApi;
use Bluemmb\Faker\PicsumPhotosProvider;
use Xylis\FakerCinema\Provider\Movie as FakerMovieProvider;
use Xylis\FakerCinema\Provider\TvShow as FakerTvShowProvider;
use Xylis\FakerCinema\Provider\Person as FakerPersonProvider;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class Oflix extends Fixture
{

        /**
     * Service OmdbApi
     *
     * @var OmdbApi
     */
    private $omdbApi;

    /**
    * Constructor
    */
    public function __construct(OmdbApi $omdbApi)
    {
        $this->omdbApi = $omdbApi;
    }
    
    /**
     * Création de donnée
     *
     * @param ObjectManager $manager
     * @return void
     */
    public function load(ObjectManager $manager): void
    {
        $faker = \Faker\Factory::create();
        $fakerFr = \Faker\Factory::create('fr_FR');
     
        // * les providers vont ajouter des méthodes avec de nouvelles fausses données
        $faker->addProvider(new PicsumPhotosProvider($faker));
        // ? on utilise le use pour donner un alias à la classe, que PHP ne se trompe pas avec notre entité
        $faker->addProvider(new FakerMovieProvider ($faker));
        $faker->addProvider(new FakerPersonProvider($faker));
        $faker->addProvider(new FakerTvShowProvider($faker));

        $admin = new User();
        $admin->setEmail("admin@admin.com");
        $admin->setPassword('$2y$13$IQUJwP4xPesp/zEXn7mdHeUdU8hXtI/BTnBRSyuWlDfsBNj84/4OC');
        $admin->setRoles(['ROLE_ADMIN']);

        $manager->persist($admin);

        $managerUser = new User();
        $managerUser->setEmail("manager@manager.com");
        $managerUser->setPassword('$2y$13$OAFR2QZwMqf.vtRiRFF9Ke9HgXjNLjwOtWddjhgvt4n0Zx7DYolEu');
        $managerUser->setRoles(['ROLE_MANAGER']);

        $manager->persist($managerUser);

        $user = new User();
        $user->setEmail("user@user.com");
        $user->setPassword('$2y$13$3umpdLm9PF7B3Ec9SRG6M.Ur835CcGzNV5lfvas7/9kGFf356g44K');
        $user->setRoles(['ROLE_USER']);

        $manager->persist($user);



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

            $actorFullName = $faker->actor();// Cate Blanchett
            $actorNames = explode(" ", $actorFullName);
            $newPerson->setFirstname($actorNames[0]);
            $newPerson->setLastname($actorNames[1]);


            $manager->persist($newPerson);

            $allPerson[] = $newPerson; 
        }

        /** @var Movie[] $allMovies */
        $allMovies = [];
        for ($i=0; $i < 10; $i++) { 
                
            $newMovie = new Movie();

            
            $newMovie->setDuration(mt_rand(10, 360));
            $newMovie->setRating(mt_rand(0,50) / 10);
            $newMovie->setSummary($fakerFr->realText());
            $newMovie->setSynopsis($fakerFr->realText());
            $newMovie->setReleaseDate(new DateTime("1970-01-01"));
            $newMovie->setCountry("FR");
            $defaultUrl = "https://amc-theatres-res.cloudinary.com/amc-cdn/static/images/fallbacks/DefaultOneSheetPoster.jpg";
            $picsumDefaultUrl = "https://picsum.photos/200/300";
            
            $picsumSeededUrl = "https://picsum.photos/seed/aza".$i."/200/300";

            $fakerPicsumSeededUrl = $faker->imageUrl(200,300, 'aza-' . $i);
            $newMovie->setPoster($fakerPicsumSeededUrl);

            $randomType = $allTypes[mt_rand(0, count($allTypes)-1)];
            $newMovie->setType($randomType);

            if ($randomType->getName() === "série"){
                $newMovie->setTitle($faker->tvShow());
            } else {
                $newMovie->setTitle($faker->movie());
            }

            // $omdbApiModel = $this->omdbApi->fetch("aaaaa");
            // dd($omdbApiModel->getPoster());
            $omdbApiModel = $this->omdbApi->fetch($newMovie->getTitle());
            // dd($omdbApiModel);
            $newMovie->setPoster($omdbApiModel->getPoster());


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

        foreach ($allMovies as $movie) {

           $randomNbGenre = mt_rand(3,5);
           for ($i=0; $i < $randomNbGenre; $i++) { 

            $randomGenre = $allGenre[mt_rand(0, count($allGenre)-1)];

            $movie->addGenre($randomGenre);      
        }
    }

    foreach ($allMovies as $movie) {
        
        if ($movie->getType()->getName() == "série")
        {
            $randomNbSeason = mt_rand(3,10);
            for ($i=1; $i <= $randomNbSeason; $i++) { 
                
                $newSeason = new Season();

                $newSeason->setNumber($i);
                $newSeason->setNbEpisodes(mt_rand(12, 24));

                $movie->addSeason($newSeason);

                $manager->persist($newSeason);
            }
        }
    }

        $manager->flush();
    }
}
