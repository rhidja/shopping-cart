<?php
declare(strict_types=1);

namespace App\Tests\Controller;

use PHPUnit\Framework\Attributes\Depends;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\Response;

class ProduitControllerTest extends WebTestCase
{
    private KernelBrowser |null $client = null;

    public function setUp(): void
    {
        $this->client = static::createClient();
    }

    public function testIndex(): Crawler
    {
        $crawler = $this->client->request('GET', '/');
        static::assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        static::assertSame('List of products', $crawler->filter('title')->text());
        static::assertEquals(12, $crawler->filter('figure.card')->count());

        return $crawler;
    }

    #[Depends('testIndex')]
    public function testVoirDetailBouton($crawler): void
    {
        $links = $crawler->filter('a:contains("Show product")');
        $titles = $crawler->filter('figcaption h4');

        for ($i=0; $i < count($links); $i++) {
            $crawler = $this->client->click($links->eq($i)->link());
            $this->assertSame("Product details", $crawler->filter('title')->text());
            $this->assertSame($titles->eq($i)->text(), $crawler->filter('h3.card-title')->text());
        }
    }

    #[Depends('testIndex')]
    public function testVoirDetailLienTitre($crawler): void
    {
        $titles = $crawler->filter('figcaption h4 a');

        for ($i=0; $i < count($titles); $i++) {
            $crawler = $this->client->click($titles->eq($i)->link());
            $this->assertSame("Product details", $crawler->filter('title')->text());
            $this->assertSame($titles->eq($i)->text(), $crawler->filter('h3.card-title')->text());
        }
    }

    public function testShow(): Crawler
    {
        $crawler = $this->client->request('GET', '/ipad');
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertSame("Product details", $crawler->filter('title')->text());

        return $crawler;
    }

    #[Depends('testShow')]
    public function testRetourListProduits($crawler): void
    {
        $link = $crawler->filter('a[title="List of products"]')->attr('href');
        $crawler = $this->client->request('GET', $link);
        static::assertSame('List of products', $crawler->filter('title')->text());
        static::assertEquals(12, $crawler->filter('figure.card')->count());
    }

    public function testNotFound(): void
    {
        $crawler = $this->client->request('GET', '/1111111');
        static::assertTrue($this->client->getResponse()->isRedirect());
        $crawler = $this->client->followRedirect();
    }
}
