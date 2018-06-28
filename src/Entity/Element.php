<?php

namespace App\Entity;

class Element
{
    private $quantity;
    private $produit;

    public function getQuantity()
    {
        return $this->quantity;
    }

    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getProduit()
    {
        return $this->produit;
    }

    public function setProduit($produit)
    {
        $this->produit = $produit;

        return $this;
    }

    public function getSousTotal()
    {
        return $this->produit->getPrix() * $this->quantity;
    }
}
