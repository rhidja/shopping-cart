<?php

// tests/Controller/PanierControllerTest.php
namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class PanierControllerTest extends WebTestCase
{
    private $client = null;

    public function setUp()
    {
        $this->client = static::createClient();
    }

    public function testIndex()
    {
        $this->client = static::createClient();
        $crawler = $this->client->request('GET', '/panier/');
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertSame('Mon panier', $crawler->filter('title')->text());
        $this->assertEquals(1, $crawler->filter('tbody tr')->count());
        $this->assertSame('Votre panier est vide', trim($crawler->filter('tbody tr')->text()));

        return $crawler;
    }

    /**
     * @depends testIndex
     */
    public function testContinuerShoping($crawler)
    {
        $link = $crawler->filter('a[title="Continuer le shopping"]')->attr('href');
        $crawler = $this->client->request('GET', $link);
        $this->assertSame('Liste des produits', $crawler->filter('title')->text());
        $this->assertEquals(12, $crawler->filter('figure.card')->count());
    }

    public function testAjouter()
    {
        $this->client = static::createClient();
        $crawler = $this->client->request('GET', '/produits/1');
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertSame("Détail d'un produit", $crawler->filter('title')->text());

        $form = $crawler->selectButton('Add to cart')->form();

        // Hydrater le formulaire
        $form['element[quantity]'] = mt_rand(1,5);
        $form['element[produit]']->select(mt_rand(1,12));

        $this->client->submit($form);


        $this->assertTrue($this->client->getResponse()->isRedirect());
        $crawler = $this->client->followRedirect();

        $this->assertSame('Mon panier', $crawler->filter('title')->text());
        $this->assertEquals(1, $crawler->filter('tbody tr')->count());
        $this->assertNotSame('Votre panier est vide', trim($crawler->filter('tbody tr')->text()));

        return $crawler;
    }

    /**
     * @depends testAjouter
     */
    public function testViderPanier($crawler)
    {
        $form = $crawler->selectButton('Vider le panier')->form();
        $this->client->submit($form);

        $this->assertTrue($this->client->getResponse()->isRedirect());
        $crawler = $this->client->followRedirect();

        $this->assertSame('Mon panier', $crawler->filter('title')->text());
        $this->assertEquals(1, $crawler->filter('tbody tr')->count());
        $this->assertSame('Votre panier est vide', trim($crawler->filter('tbody tr')->text()));
    }
}
