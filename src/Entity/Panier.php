<?php
declare(strict_types=1);

namespace App\Entity;

use App\Repository\PanierRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PanierRepository::class)]
#[ORM\Table(name: 'app_panier')]
class Panier
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    /**
     * @var Collection<int, Element>
     */
    #[ORM\OneToMany(targetEntity: Element::class, mappedBy: 'panier', cascade: ['persist'], orphanRemoval: true)]
    private Collection $elements;

    public function __construct()
    {
        $this->elements = new ArrayCollection();
    }

    public function getTotal(): float
    {
        $total = 0;
        foreach ($this->elements as $element) {
            $total += $element->getQuantity() * $element->getProduit()->getPrix();
        }

        return $total;
    }

    public function getCount(): float
    {
        $count = 0;
        foreach ($this->elements as $element) {
            $count += $element->getQuantity();
        }

        return $count;
    }

    /**
     * @return Collection<int, Element>
     */
    public function getTasks(): Collection
    {
        return $this->elements;
    }

    public function addTask(Element $element): static
    {
        if (!$this->elements->contains($element)) {
            $this->elements->add($element);
            $element->setPanier($this);
        }

        return $this;
    }

    public function removeTask(Element $element): static
    {
        if ($this->elements->removeElement($element)) {
            // set the owning side to null (unless already changed)
            if ($element->getPanier() === $this) {
                $element->setPanier(null);
            }
        }

        return $this;
    }
}
