<?php
declare(strict_types=1);

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

        return $crawler;
    }

    /**
     * @depends testIndex
     */
    public function testVoirDetailBouton($crawler)
    {
        $links = $crawler->filter('a:contains("Voir la fiche")');
        $titles = $crawler->filter('figcaption h4');

        for ($i=0; $i < count($links); $i++) {
            $crawler = $this->client->click($links->eq($i)->link());
            $this->assertSame("Détail d'un produit", $crawler->filter('title')->text());
            $this->assertSame($titles->eq($i)->text(), $crawler->filter('h3.card-title')->text());
        }
    }

    /**
     * @depends testIndex
     */
    public function testVoirDetailLienTitre($crawler)
    {
        $titles = $crawler->filter('figcaption h4 a');

        for ($i=0; $i < count($titles); $i++) {
            $crawler = $this->client->click($titles->eq($i)->link());
            $this->assertSame("Détail d'un produit", $crawler->filter('title')->text());
            $this->assertSame($titles->eq($i)->text(), $crawler->filter('h3.card-title')->text());
        }
    }

    public function testShow()
    {
        $crawler = $this->client->request('GET', '/produits/1');
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertSame("Détail d'un produit", $crawler->filter('title')->text());

        return $crawler;
    }

    /**
     * @depends testShow
     */
    public function testRetourListProduits($crawler)
    {
        $link = $crawler->filter('a[title="Liste des produits"]')->attr('href');
        $crawler = $this->client->request('GET', $link);
        $this->assertSame('Liste des produits', $crawler->filter('title')->text());
        $this->assertEquals(12, $crawler->filter('figure.card')->count());
    }

    public function testNotFound()
    {
        $crawler = $this->client->request('GET', '/produits/1111111');
        $this->assertTrue($this->client->getResponse()->isRedirect());
        $crawler = $this->client->followRedirect();
    }
}
