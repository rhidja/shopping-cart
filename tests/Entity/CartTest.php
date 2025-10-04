<?php
declare(strict_types=1);

namespace App\Tests\Entity;

use App\Entity\Cart;
use App\Entity\CartItem;
use App\Entity\Product;
use PHPUnit\Framework\TestCase;

class CartTest extends TestCase
{
    public function testSousTotal(): void
    {
        $cart  = new Cart();

        $nbrElements = mt_rand(1, 10);

        $total = 0;
        for ($i=0; $i < $nbrElements; $i++) {

            $quantity = (int)mt_rand(1, 5);
            $prix = mt_rand(10, 1000);
            $total += $quantity * $prix;

            $product = new Product();
            $product->setPrice($prix);

            $item = new CartItem();
            $item->setProduct($product);
            $item->setQuantity($quantity);

            $cart->addItem($item);
        }

        $this->assertEquals($total, $cart->getTotal());
    }

    public function testCount(): void
    {
        $cart  = new Cart();

        $nbrElements = mt_rand(1, 10);

        $total = 0;
        for ($i=0; $i < $nbrElements; $i++) {

            $quantity = (int)mt_rand(1, 5);
            $prix = mt_rand(10, 1000);
            $total += $quantity;

            $product = new Product();
            $product->setPrice($prix);

            $item = new CartItem();
            $item->setProduct($product);
            $item->setQuantity($quantity);

            $cart->addItem($item);
        }

        $this->assertEquals($total, $cart->getCount());
    }
}
