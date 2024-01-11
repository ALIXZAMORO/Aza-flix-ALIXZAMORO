<?php

namespace App\DataFixtures;

use App\Entity\Genre;
use App\Entity\Type;
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
        $genres = [
            'horreur',
            'comique',
            'sf'
        ];

        for ($i=1; $i <= 10; $i++) { 
            
            $newGenre = new Genre();

            $newGenre->setName("Genre #" . $i);

            $manager->persist($newGenre);
        }

        $types = ["film", "série"];

        foreach ($types as $type) {
            
            $newType = new Type();

            $newType->setName($type);

            $manager->persist($newType);
        }

        $manager->flush();
    }
}
