<?php
declare(strict_types=1);

namespace App\Tests\Entity;

use App\Entity\CartItem;
use App\Entity\Product;
use PHPUnit\Framework\TestCase;

class CartItemTest extends TestCase
{
    public function testSubTotal(): void
    {
        $quantity = mt_rand(1, 10);
        $prix = mt_rand(50, 1000);
        $subTotal = $quantity * $prix;

        $product = new Product();
        $product->setPrice($prix);

        $item = new CartItem();
        $item->setProduct($product);
        $item->setQuantity($quantity);

        $this->assertEquals($subTotal, $item->getSubTotal());
    }
}
