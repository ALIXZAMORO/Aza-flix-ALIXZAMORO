<?php

namespace App\Tests\Service;

use App\Services\OmdbApi;
use App\Services\OmdbApiRomain;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class OmdbApiRomainTest extends KernelTestCase
{
    public function testSomething(): void
    {
        $kernel = self::bootKernel();

        $this->assertSame('test', $kernel->getEnvironment());

        // TODO tester le service OmdbApi
        // 1. on doit récupérer notre service
        // on est pas dans symfony, on est dans le framework de test
        // on a donc pas l'injection de dépendance
        // on va donc utiliser les raccourcies fournit par symfony à PHPUnit (framework de test)
        // * on demande au conteneur de services de nous fournir notre service
        /** @var OmdbApiRomain $omdbApi */
        $omdbApi = static::getContainer()->get(OmdbApiRomain::class);

        $infosOdmb = $omdbApi->fetch("Stranger Things");
        $posterExpected = "https://m.media-amazon.com/images/M/MV5BMDZkYmVhNjMtNWU4MC00MDQxLWE3MjYtZGMzZWI1ZjhlOWJmXkEyXkFqcGdeQXVyMTkxNjUyNQ@@._V1_SX300.jpg";

        $this->assertEquals($posterExpected, $infosOdmb['Poster']);
        // ! "http://www.omdbapi.com/?t=Stranger%20Things&apiKey=xxxxx".
        // * il faut bien renseigner la clé dans le .env.test

        $infosOdmb = $omdbApi->fetch("aaaaaaaa");
        $expected = "Movie not found!";
        $this->assertEquals($expected, $infosOdmb['Error']);
        

    }
}
