<?php
declare(strict_types=1);

namespace App\Tests\Controller\Api;

use PHPUnit\Framework\Attributes\Depends;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class ProduitControllerTest extends WebTestCase
{
    private KernelBrowser $client;

    public function setUp(): void
    {
        $this->client = static::createClient();
    }

    public function testApiIndex(): array
    {
        $this->client->request('GET', '/api/produits/');

        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $this->assertContentType();

        $produits = json_decode((string) $this->client->getResponse()->getContent(), true);
        $this->assertCount(12, $produits);

        $this->hasKeys($produits, ['id', 'nom', 'description']);
        $this->notEmpty($produits, ['id', 'nom']);

        return $produits;
    }

    #[Depends('testApiIndex')]
    public function testApiShow($produits): void
    {
        foreach ($produits as $produit) {
            $this->client->request('GET', '/api/produits/'.$produit['id']);
            $this->assertTrue($this->client->getResponse()->isSuccessful());
            $this->assertContentType();

            $prod = json_decode((string) $this->client->getResponse()->getContent(), true);
            $keys = array_keys($produit);
            $this->hasKeys([$prod], $keys);
            $this->notEmpty([$produit], ['id', 'nom']);
            $this->compareValues($produit, $prod);
        }
    }

    #[Depends('testApiIndex')]
    public function testNotFound($produits): void
    {
        $ids = array_column($produits, 'id');
        $id = mt_rand(1, 1000000);

        while (in_array($id, $ids)) {
            $id = mt_rand(1, 1000000);
        }

        $this->client->request('GET', '/api/produits/'.$id);
        $this->assertEquals(Response::HTTP_NOT_FOUND, $this->client->getResponse()->getStatusCode());
        $this->assertStringContainsString(
            "Aucun produit ne correspond a cette Id",
            $this->client->getResponse()->getContent()
        );
    }

    private function hasKeys($produits, $keys): void
    {
        foreach ($produits as $produit) {
            foreach ($keys as $key) {
                $this->assertArrayHasKey($key, $produit);
            }
        }
    }

    public function notEmpty($produits, $keys): void
    {
        foreach ($produits as $produit) {
            foreach ($keys as $key) {
                $this->assertNotEmpty($produit[$key]);
                $this->assertNotNull($produit[$key]);
            }
        }
    }

    public function compareValues($produit, $prod): void
    {
        foreach ($produit as $key => $value) {
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
