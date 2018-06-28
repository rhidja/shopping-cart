<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;

class Panier
{
    private $elements;

    public function __construct()
    {
        $this->elements = new ArrayCollection();
    }

    public function getTotal()
    {
        $total = 0;
        foreach ($this->elements as $element) {
            $total += $element->getQuantity() * $element->getProduit()->getPrix();
        }

        return $total;
    }

    /**
     * Add element
     *
     * @param \App\Entity\Element $element
     *
     * @return Project
     */
    public function addElement(\App\Entity\Element $element)
    {
        $this->elements[] = $element;

        return $this;
    }

    /**
     * Remove element
     *
     * @param \App\Entity\Element $element
     */
    public function removeElement(\App\Entity\Element $element)
    {
        $this->elements->removeElement($element);
    }

    /**
     * Get elements
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getElements()
    {
        return $this->elements;
    }
}
