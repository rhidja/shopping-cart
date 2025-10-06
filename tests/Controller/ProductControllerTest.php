<?php

declare(strict_types=1);

namespace App\Tests\Controller;

use PHPUnit\Framework\Attributes\Depends;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DomCrawler\Crawler;

class ProductControllerTest extends WebTestCase
{
    private ?KernelBrowser $client = null;

    public function setUp(): void
    {
        $this->client = static::createClient();
    }

    public function testIndex(): Crawler
    {
        $crawler = $this->client->request('GET', '/');

        static::assertResponseIsSuccessful();
        static::assertPageTitleSame('List of products');
        static::assertEquals(12, $crawler->filter('figure.card')->count());

        return $crawler;
    }

    #[Depends('testIndex')]
    public function testVoirDetailBouton(Crawler $crawler): void
    {
        $links = $crawler->filter('a:contains("Show product")');
        $titles = $crawler->filter('figcaption h4');

        for ($i = 0; $i < count($links); ++$i) {
            $crawler = $this->client->click($links->eq($i)->link());

            static::assertPageTitleSame('Product details');
            static::assertSame($titles->eq($i)->text(), $crawler->filter('h3.card-title')->text());
        }
    }

    #[Depends('testIndex')]
    public function testVoirDetailLienTitre(Crawler $crawler): void
    {
        $titles = $crawler->filter('figcaption h4 a');

        for ($i = 0; $i < count($titles); ++$i) {
            $crawler = $this->client->click($titles->eq($i)->link());

            static::assertPageTitleSame('Product details');
            static::assertSame($titles->eq($i)->text(), $crawler->filter('h3.card-title')->text());
        }
    }

    public function testShow(): Crawler
    {
        $crawler = $this->client->request('GET', '/ipad');

        static::assertResponseIsSuccessful();
        static::assertPageTitleSame('Product details');

        return $crawler;
    }

    #[Depends('testShow')]
    public function testRetourListProduits(Crawler $crawler): void
    {
        $link = $crawler->filter('a[title="List of products"]')->attr('href');
        $crawler = $this->client->request('GET', $link);

        static::assertPageTitleSame('List of products');
        static::assertEquals(12, $crawler->filter('figure.card')->count());
    }

    public function testNotFound(): void
    {
        $this->client->request('GET', '/1111111');

        static::assertResponseRedirects();
        $this->client->followRedirect();
    }
}
