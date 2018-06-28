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
        $this->assertSame('Votre panier est vide', trim($crawler->filter('tbody tr')->text()));

        return $crawler;
    }

    public function testPlus()
    {
        $this->client = static::createClient();
        $crawler = $this->client->request('GET', '/produits/1');
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertSame("Détail d'un produit", $crawler->filter('title')->text());

        $form = $crawler->selectButton('Add to cart')->form();

        // Hydrater le formulaire
        $form['element[quantity]'] = 2;
        $form['element[produit]']->select(1);

        $this->client->submit($form);


        $this->assertTrue($this->client->getResponse()->isRedirect());
        $crawler = $this->client->followRedirect();

        $this->assertSame('Mon panier', $crawler->filter('title')->text());
        $this->assertEquals(1, $crawler->filter('tbody tr')->count());
        $this->assertNotSame('Votre panier est vide', trim($crawler->filter('tbody tr')->text()));
    }
}
