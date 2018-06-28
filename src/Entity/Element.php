<?php

namespace App\Entity;

use App\Entity\Produit;

class Element
{
    private $quantity;
    private $produit;

    public function getQuantity(): int
    {
        return (int)$this->quantity;
    }

    public function setQuantity($quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getProduit()
    {
        return $this->produit;
    }

    public function setProduit(Produit $produit): self
    {
        $this->produit = $produit;

        return $this;
    }

    public function getSousTotal(): float
    {
        return $this->produit->getPrix() * $this->quantity;
    }
}
