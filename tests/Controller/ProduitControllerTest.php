<?php

// tests/Controller/PostControllerTest.php
namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class ProduitControllerTest extends WebTestCase
{
    private $client = null;

    public function setUp()
    {
        $this->client = static::createClient();
    }

    public function testIndex()
    {
        $this->client = static::createClient();
        $crawler = $this->client->request('GET', '/produits/');
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertSame('Liste des produits', $crawler->filter('title')->text());
        $this->assertEquals(12, $crawler->filter('figure.card')->count());

        return $crawler;;
    }

    /**
     * @depends testIndex
     */
    public function testBtnVoirDetail($crawler)
    {
        $link = $crawler->filter('a[title="Voir la fiche"]')->attr('href');
        $crawler = $this->client->request('GET', $link);
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertSame("Détail d'un produit", $crawler->filter('title')->text());
    }

    public function testShow()
    {
        $crawler = $this->client->request('GET', '/produits/1');
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertSame("Détail d'un produit", $crawler->filter('title')->text());
    }

    public function testNotFound()
    {
        $crawler = $this->client->request('GET', '/produits/1111111');
        $this->assertTrue($this->client->getResponse()->isRedirect());
        $crawler = $this->client->followRedirect();
    }
}
