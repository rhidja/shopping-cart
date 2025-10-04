<?php
declare(strict_types=1);

namespace App\Tests\Controller\Api;

use PHPUnit\Framework\Attributes\Depends;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class ProductControllerTest extends WebTestCase
{
    private KernelBrowser $client;

    public function setUp(): void
    {
        $this->client = static::createClient();
    }

    public function testApiIndex(): array
    {
        $this->client->request('GET', '/api/products/');

        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $this->assertContentType();

        $products = json_decode((string) $this->client->getResponse()->getContent(), true);
        $this->assertCount(12, $products);

        $this->hasKeys($products, ['id', 'name', 'description']);
        $this->notEmpty($products, ['id', 'name']);

        return $products;
    }

    #[Depends('testApiIndex')]
    public function testApiShow($products): void
    {
        foreach ($products as $product) {
            $this->client->request('GET', '/api/products/'.$product['id']);
            $this->assertTrue($this->client->getResponse()->isSuccessful());
            $this->assertContentType();

            $prod = json_decode((string) $this->client->getResponse()->getContent(), true);
            $keys = array_keys($product);
            $this->hasKeys([$prod], $keys);
            $this->notEmpty([$product], ['id', 'name']);
            $this->compareValues($product, $prod);
        }
    }

    #[Depends('testApiIndex')]
    public function testNotFound($products): void
    {
        $ids = array_column($products, 'id');
        $id = mt_rand(1, 1000000);

        while (in_array($id, $ids)) {
            $id = mt_rand(1, 1000000);
        }

        $this->client->request('GET', '/api/products/'.$id);
        $this->assertEquals(Response::HTTP_NOT_FOUND, $this->client->getResponse()->getStatusCode());
        $this->assertStringContainsString(
            "No product matches this ID.",
            $this->client->getResponse()->getContent()
        );
    }

    private function hasKeys($products, $keys): void
    {
        foreach ($products as $product) {
            foreach ($keys as $key) {
                $this->assertArrayHasKey($key, $product);
            }
        }
    }

    public function notEmpty($products, $keys): void
    {
        foreach ($products as $product) {
            foreach ($keys as $key) {
                $this->assertNotEmpty($product[$key]);
                $this->assertNotNull($product[$key]);
            }
        }
    }

    public function compareValues($product, $prod): void
    {
        foreach ($product as $key => $value) {
            $this->assertEquals($value, $prod[$key]);
        }
    }

    /**
     * Assert that the "Content-Type" header is "application/json"
     */
    private function assertContentType(): void
    {
        // Assert that the "Content-Type" header is "application/json"
        $this->assertTrue(
            $this->client->getResponse()->headers->contains(
                'Content-Type',
                'application/json'
            )
        );
    }
}
