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

    /**
     * @return array<int, array<string, string|int|null>>
     */
    public function testApiIndex(): array
    {
        $this->client->request('GET', '/api/products/');

        static::assertResponseIsSuccessful();
        $this->assertContentType();

        $products = json_decode($this->client->getResponse()->getContent(), true);
        static::assertCount(12, $products);

        $this->hasKeys($products, ['id', 'name', 'description']);
        $this->notEmpty($products, ['id', 'name']);

        return $products;
    }

    /**
     * @param array<int, array<string, string|int|null>> $products
     */
    #[Depends('testApiIndex')]
    public function testApiShow(array $products): void
    {
        foreach ($products as $product) {
            $this->client->request('GET', '/api/products/'.$product['id']);
            static::assertTrue($this->client->getResponse()->isSuccessful());
            $this->assertContentType();

            $prod = json_decode($this->client->getResponse()->getContent(), true);
            $keys = array_keys($product);

            $this->hasKeys([$prod], $keys);
            $this->notEmpty([$product], ['id', 'name']);
            $this->compareValues($product, $prod);
        }
    }

    /**
     * @param array<int, array<string, string|int|null>> $products
     */
    #[Depends('testApiIndex')]
    public function testNotFound(array $products): void
    {
        $ids = array_column($products, 'id');
        $id = mt_rand(1, 1000000);

        while (in_array($id, $ids)) {
            $id = mt_rand(1, 1000000);
        }

        $this->client->request('GET', '/api/products/'.$id);

        static::assertEquals(Response::HTTP_NOT_FOUND, $this->client->getResponse()->getStatusCode());
        static::assertStringContainsString(
            'No product matches this ID.',
            $this->client->getResponse()->getContent()
        );
    }

    /**
     * @param array<int, array<string, string|int|null>> $products
     * @param string[]                                   $keys
     */
    private function hasKeys(array $products, array $keys): void
    {
        foreach ($products as $product) {
            foreach ($keys as $key) {
                static::assertArrayHasKey($key, $product);
            }
        }
    }

    /**
     * @param array<int, array<string, string|int|null>> $products
     * @param string[]                                   $keys
     */
    public function notEmpty(array $products, array $keys): void
    {
        foreach ($products as $product) {
            foreach ($keys as $key) {
                static::assertNotEmpty($product[$key]);
                static::assertNotNull($product[$key]);
            }
        }
    }

    /**
     * @param array<string, string|int|null> $product
     * @param array<string, string|int|null> $prod
     */
    public function compareValues(array $product, array $prod): void
    {
        foreach ($product as $key => $value) {
            static::assertEquals($value, $prod[$key]);
        }
    }

    /**
     * Assert that the "Content-Type" header is "application/json".
     */
    private function assertContentType(): void
    {
        // Assert that the "Content-Type" header is "application/json"
        static::assertTrue(
            $this->client->getResponse()->headers->contains(
                'Content-Type',
                'application/json'
            )
        );
    }
}
