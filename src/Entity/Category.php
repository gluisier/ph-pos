<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
class Category extends DisplayableItem
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToMany(targetEntity: Item::class, mappedBy: 'category')]
    protected Collection $items;

    /**
     * @var Collection<int, Item>
     */
    #[ORM\ManyToMany(targetEntity: Item::class, mappedBy: 'attributes')]
    private Collection $variants;


    public function __construct()
    {
        $this->items = new ArrayCollection();
        $this->variants = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, Item>
     */
    public function getItems(): Collection
    {
        return $this->items;
    }

    public function addItem(Item $item): static
    {
        if (!$this->items->contains($item)) {
            $this->items->add($item);
            $item->setCategory($this);
        }

        return $this;
    }

    public function removeItem(Item $item): static
    {
        if ($this->items->removeElement($item)) {
            // set the owning side to null (unless already changed)
            if ($item->getCategory() === $this) {
                $item->setCategory(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Item>
     */
    public function getVariants(): Collection
    {
        return $this->variants;
    }

    public function addVariant(Item $variants): static
    {
        if (!$this->variants->contains($variants)) {
            $this->variants->add($variants);
            $variants->addAttribute($this);
        }

        return $this;
    }

    public function removeVariants(Item $variants): static
    {
        if ($this->variants->removeElement($variants)) {
            $variants->removeAttribute($this);
        }

        return $this;
    }

    public function areItemsPriceImmutable(): bool
    {
        if ($this->items->isEmpty()) {
            return true;
        }

        $firstPrice = $this->items->first()->getPrice();
        $result = $this->items->findFirst(function (mixed $key, Item $item) use ($firstPrice) {
            return $item->getPrice() != $firstPrice;
        });

        return empty($result);
    }
}
