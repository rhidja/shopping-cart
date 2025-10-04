<?php
declare(strict_types=1);

namespace App\Tests\Controller;

use PHPUnit\Framework\Attributes\Depends;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\Response;

class CartControllerTest extends WebTestCase
{
    private $client = null;

    public function setUp(): void
    {
        $this->client = static::createClient();
    }

    public function testIndex()
    {
        $crawler = $this->client->request('GET', '/cart/');
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertSame('My Cart', $crawler->filter('title')->text());
        $this->assertEquals(1, $crawler->filter('tbody tr')->count());
        $this->assertSame('Your cart is empty', trim((string) $crawler->filter('tbody tr')->text()));

        return $crawler;
    }

    #[Depends('testIndex')]
    public function testContinueShopping($crawler): void
    {
        $link = $crawler->filter('a[title="Continue shopping"]')->attr('href');
        $crawler = $this->client->request('GET', $link);
        $this->assertSame('List of products', $crawler->filter('title')->text());
        $this->assertEquals(12, $crawler->filter('figure.card')->count());
    }

    public function testAddItem(): Crawler
    {
        $crawler = $this->client->request('GET', '/1');
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertSame("Product details", $crawler->filter('title')->text());

        $form = $crawler->selectButton('Add to cart')->form();

        // Hydrater le formulaire
        $form['item[quantity]'] = mt_rand(1,5);
        $form['item[product]']->select((string)mt_rand(1,12));

        $this->client->submit($form);


        $this->assertTrue($this->client->getResponse()->isRedirect());
        $crawler = $this->client->followRedirect();

        $this->assertSame('My Cart', $crawler->filter('title')->text());
        $this->assertEquals(1, $crawler->filter('tbody tr')->count());
        $this->assertNotSame('Your cart is empty', trim((string) $crawler->filter('tbody tr')->text()));

        return $crawler;
    }

    #[Depends('testAddItem')]
    public function testEmptyCart($crawler): void
    {
        $form = $crawler->selectButton('Empty the cart')->form();
        $this->client->submit($form);

        $this->assertTrue($this->client->getResponse()->isRedirect());
        $crawler = $this->client->followRedirect();

        $this->assertSame('My Cart', $crawler->filter('title')->text());
        $this->assertEquals(1, $crawler->filter('tbody tr')->count());
        $this->assertSame('Your cart is empty', trim((string) $crawler->filter('tbody tr')->text()));
    }
}
