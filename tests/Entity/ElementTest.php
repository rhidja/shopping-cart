<?php
// tests/Entity/ElementTest.php
namespace App\Tests\Entity;

use App\Entity\Element;
use App\Entity\Produit;
use PHPUnit\Framework\TestCase;

class ElementTest extends TestCase
{
    public function testSousTotal()
    {
        $quantity = mt_rand(1, 10);
        $prix = mt_rand(50, 1000);
        $sousTotal = $quantity * $prix;

        $produit = new Produit();
        $produit->setPrix($prix);

        $element = new Element();
        $element->setProduit($produit);
        $element->setQuantity($quantity);

        $this->assertEquals($sousTotal, $element->getSousTotal());
    }
}
