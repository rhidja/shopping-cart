<?php
// tests/Controller/Api/ProduitControllerTest.php
namespace App\Tests\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class ProduitControllerTest extends WebTestCase
{
    private $client = null;

    public function setUp()
    {
        $this->client = static::createClient();
    }

    public function testApiIndex()
    {
        $this->client->request('GET', '/api/produits/');
        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $this->assertContentType();

        $produits = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertCount(12, $produits);

        $this->hasKeys($produits, ['id', 'nom', 'description']);
        $this->notEmpty($produits, ['id', 'nom']);

        return $produits;
    }

    /**
     * @depends testApiIndex
     */
    public function testApiShow($produits)
    {
        foreach ($produits as $produit) {
            $this->client->request('GET', '/api/produits/'.$produit['id']);
            $this->assertTrue($this->client->getResponse()->isSuccessful());
            $this->assertContentType();

            $prod = json_decode($this->client->getResponse()->getContent(), true);
            $keys = array_keys($produit);
            $this->hasKeys([$prod], $keys);
            $this->notEmpty([$produit], ['id', 'nom']);
            $this->compareValues($produit, $prod);
        }
    }

    /**
     * @depends testApiIndex
     */
    public function testNotFound($produits)
    {
        $ids = array_column($produits, 'id');
        $id = mt_rand(1, 1000000);

        while (in_array($id, $ids)) {
            $id = mt_rand(1, 1000000);
        }

        $this->client->request('GET', '/api/produits/'.$id);
        $this->assertEquals(Response::HTTP_NOT_FOUND, $this->client->getResponse()->getStatusCode());
        $this->assertContains(
            "Aucun produit ne correspond a cette Id",
            $this->client->getResponse()->getContent()
        );
    }

    private function hasKeys($produits, $keys)
    {
        foreach ($produits as $produit) {
            foreach ($keys as $key) {
                $this->assertArrayHasKey($key, $produit);
            }
        }
    }

    public function notEmpty($produits, $keys)
    {
        foreach ($produits as $produit) {
            foreach ($keys as $key) {
                $this->assertNotEmpty($produit[$key]);
                $this->assertNotNull($produit[$key]);
            }
        }
    }

    public function compareValues($produit, $prod)
    {
        foreach ($produit as $key => $value) {
            $this->assertEquals($value, $prod[$key]);
        }
    }

    /**
     * Assert that the "Content-Type" header is "application/json"
     */
    private function assertContentType()
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
