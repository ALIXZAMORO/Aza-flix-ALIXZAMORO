<?php

namespace App\Tests\Front;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class MainTest extends WebTestCase
{
    public function testHome(): void
    {
        // 1. création client HTTP
        $client = static::createClient();
        // 2. création et exécution requete HTTP
        // GET /
        $crawler = $client->request('GET', '/');

        // code de retour == 200 ?
        $this->assertResponseIsSuccessful();
        // la page contient une balise h1 avec le texte dedans ?
        $this->assertSelectorTextContains('h1', 'Films, séries TV et popcorn en illimité.');


    }
    public function testShow(): void
    {
        // 1. création client HTTP
        $client = static::createClient();
        // 2. création et exécution requete HTTP
        // GET /
        $crawler = $client->request('GET', '/movies/1');

        // code de retour == 200 ?
        $this->assertResponseIsSuccessful();
        // la page contient une balise h3 avec le texte dedans ?
        $this->assertSelectorTextContains('h3', 'Community');
    }

    public function testAddReviewWithoutSecurity()
    {
        // TODO : aller sur l'URL /movies/1/review/add
        // 1. création client HTTP
        $client = static::createClient();
        // 2. création et exécution requete HTTP
        // GET /movies/1/review/add
        $crawler = $client->request('GET', '/movies/1/review/add');

        $this->assertResponseIsSuccessful();

        // TODO : remplir le formulaire
        // ? https://symfony.com/doc/current/testing.html#submitting-forms
        // je pointe sur un bouton submit
        $submitButton = $crawler->selectButton('Ajouter');
        // j'en récupère le formulaire associé
        $form = $submitButton->form();
        // dd($form);
        $form['review[username]'] = 'Boris';
        $form['review[email]'] = 'boris@boris.com';
        $form['review[content]'] = "ce soir chez Boris, c'est soirée Disco";
        $form['review[rating]'] = 4;
        // ! il faut mettre les options dans l'ordre sinon ERREUR
        // TODO : JB va faire un issue car c'est ce que montre la doc
        // ? https://symfony.com/doc/5.4/components/dom_crawler.html#forms
        
        // $form->setValues(['review' => ['reactions' => ['dream', 'cry']]]);
        // $form['review[reactions]']->select(['dream', 'cry']);
        // ["smile", "cry", "think"];
        $form['review[reactions]'] = ["smile", "cry"];
        // $form['review[reactions]'] = "smile,think";
        $form['review[watchedAt]'] = '2023-05-23';
        
        // TODO : soumettre le formulaire
        $client->submit($form);

        // TODO : verfier le code de retour
        // 200
        // $this->assertResponseIsSuccessful();
        // à la fin de l'insrtion de donnée veant d'un formulaire, on fait un redirect
        // ce qui correspond au code 302
        $this->assertResponseRedirects();

        // option : vérifier en BDD l'existance du review
        // option : vérifier l'affichage du review


    }
}
