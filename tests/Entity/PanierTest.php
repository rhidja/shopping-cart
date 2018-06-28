<?php
// tests/Entity/ElementTest.php
namespace App\Tests\Entity;

use App\Entity\Panier;
use App\Entity\Element;
use App\Entity\Produit;
use PHPUnit\Framework\TestCase;

class PanierTest extends TestCase
{
    public function testSousTotal()
    {
        $panier  = new Panier();

        $nbrElements = mt_rand(1, 10);

        $total = 0;
        for ($i=0; $i < $nbrElements; $i++) {

            $quantity = (int)mt_rand(1, 5);
            $prix = mt_rand(10, 1000);
            $total += $quantity * $prix;

            $produit = new Produit();
            $produit->setPrix($prix);

            $element = new Element();
            $element->setProduit($produit);
            $element->setQuantity($quantity);

            $panier->addElement($element);
        }

        $this->assertEquals($total, $panier->getTotal());
    }

    public function testCount()
    {
        $panier  = new Panier();

        $nbrElements = mt_rand(1, 10);

        $total = 0;
        for ($i=0; $i < $nbrElements; $i++) {

            $quantity = (int)mt_rand(1, 5);
            $prix = mt_rand(10, 1000);
            $total += $quantity;

            $produit = new Produit();
            $produit->setPrix($prix);

            $element = new Element();
            $element->setProduit($produit);
            $element->setQuantity($quantity);

            $panier->addElement($element);
        }

        $this->assertEquals($total, $panier->getCount());
    }
}
