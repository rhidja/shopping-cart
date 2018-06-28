<?php

// tests/Controller/PostControllerTest.php
namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

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
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertSame('Liste des produits', $crawler->filter('title')->text());
        $this->assertEquals(12, $crawler->filter('figure.card')->count());
    }
}
