<?php

namespace App\Entity;

use App\Repository\ItemRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ItemRepository::class)]
class Item extends DisplayableItem implements \JsonSerializable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::SMALLINT, nullable: true)]
    private ?int $price = null;

    #[ORM\Column(nullable: true)]
    private ?int $stock = null;

    #[ORM\Column]
    private ?bool $ticket = null;

    #[ORM\Column]
    private ?bool $available = null;

    #[ORM\Column]
    private ?bool $separatelySellable = null;

    /** NOT A PERSISTER PROPERTY */
    private ?bool $pack = null;

    #[ORM\ManyToOne(targetEntity: Category::class, inversedBy: 'items')]
    private ?Category $category = null;

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'variants')]
    private ?self $variantOf = null;

    #[ORM\OneToMany(targetEntity: self::class, mappedBy: 'variantOf', indexBy: 'id')]
    #[ORM\OrderBy(['position' => 'ASC'])]
    private Collection $variants;

    #[ORM\OneToMany(mappedBy: 'item', targetEntity: OrderLine::class)]
    private Collection $orders;

    /**
     * @var Collection<int, Category>
     */
    #[ORM\ManyToMany(targetEntity: Category::class, inversedBy: 'variants')]
    #[ORM\JoinTable('item_attribute')]
    #[ORM\JoinColumn(name: 'item_id')]
    #[ORM\InverseJoinColumn(name: 'attribute_id')]
    #[ORM\OrderBy(['position' => 'ASC'])]
    private Collection $attributes;

    /**
     * @var Collection<int, self>
     */
    #[ORM\ManyToMany(targetEntity: self::class, inversedBy: 'composing', indexBy: 'id')]
    #[ORM\JoinTable('composition')]
    #[ORM\JoinColumn(name: 'compound_id')]
    #[ORM\InverseJoinColumn(name: 'component_id')]
    #[ORM\OrderBy(['position' => 'ASC'])]
    private Collection $composedOf;

    /**
     * @var Collection<int, self>
     */
    #[ORM\ManyToMany(targetEntity: self::class, mappedBy: 'composedOf')]
    private Collection $composing;

    public function __construct()
    {
        $this->variants = new ArrayCollection();
        $this->orders = new ArrayCollection();
        $this->composedOf = new ArrayCollection();
        $this->composing = new ArrayCollection();
        $this->attributes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(?int $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getStock(): ?int
    {
        return $this->stock;
    }

    public function setStock(?int $stock): static
    {
        $this->stock = $stock;

        return $this;
    }

    public function hasTicket(): ?bool
    {
        return $this->ticket;
    }

    public function setTicket(bool $ticket): static
    {
        $this->ticket = $ticket;

        return $this;
    }

    public function isAvailable(): ?bool
    {
        return $this->available;
    }

    public function setAvailable(bool $available): static
    {
        $this->available = $available;

        return $this;
    }

    public function isSeparatelySellable(): ?bool
    {
        return $this->separatelySellable;
    }

    public function setSeparatelySellable(bool $separatelySellable): static
    {
        $this->separatelySellable = $separatelySellable;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): static
    {
        $this->category = $category;

        return $this;
    }

    public function getVariantOf(): ?self
    {
        return $this->variantOf;
    }

    public function setVariantOf(?self $variantOf): static
    {
        $this->variantOf = $variantOf;

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getVariants(): Collection
    {
        return $this->variants;
    }

    public function addVariant(self $subItem): static
    {
        if (!$this->variants->contains($subItem)) {
            $this->variants->add($subItem);
            $subItem->setVariantOf($this);
        }

        return $this;
    }

    public function removeVariant(self $subItem): static
    {
        if ($this->variants->removeElement($subItem)) {
            // set the owning side to null (unless already changed)
            if ($subItem->getVariantOf() === $this) {
                $subItem->setVariantOf(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, OrderLine>
     */
    public function getOrders(): Collection
    {
        return $this->orders;
    }

    public function addOrder(OrderLine $order): static
    {
        if (!$this->orders->contains($order)) {
            $this->orders->add($order);
            $order->setItem($this);
        }

        return $this;
    }

    public function removeOrder(OrderLine $order): static
    {
        if ($this->orders->removeElement($order)) {
            // set the owning side to null (unless already changed)
            if ($order->getItem() === $this) {
                $order->setItem(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Category>
     */
    public function getAttributes(): Collection
    {
        return $this->attributes;
    }

    public function addAttribute(Category $attribute): static
    {
        if (!$this->attributes->contains($attribute)) {
            $this->attributes->add($attribute);
        }

        return $this;
    }

    public function removeAttribute(Category $attribute): static
    {
        $this->attributes->removeElement($attribute);

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getComposedOf(): Collection
    {
        return $this->composedOf;
    }

    public function addComposedOf(self $composedOf): static
    {
        if (!$this->composedOf->contains($composedOf)) {
            $this->composedOf->add($composedOf);
        }

        return $this;
    }

    public function removeComposedOf(self $composedOf): static
    {
        $this->composedOf->removeElement($composedOf);

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getComposing(): Collection
    {
        return $this->composing;
    }

    public function addComposing(self $composing): static
    {
        if (!$this->composing->contains($composing)) {
            $this->composing->add($composing);
            $composing->addComposedOf($this);
        }

        return $this;
    }

    public function removeComposing(self $composing): static
    {
        if ($this->composing->removeElement($composing)) {
            $composing->removeComposedOf($this);
        }

        return $this;
    }

    public function areVariantsPriceImmutable(): bool
    {
        if ($this->variants->isEmpty()) {
            return true;
        }

        $firstPrice = $this->variants->first()->getPrice();
        $result = $this->variants->findFirst(function (mixed $key, Item $item) use ($firstPrice) {
            return $item->getPrice() != $firstPrice;
        });

        return empty($result);
    }

    public function isVariant(): bool
    {
        return (bool)$this->variantOf;
    }

    public function isPack(): bool
    {
        if ($this->pack !== null) {
            return $this->pack;
        }

        if ($this->composedOf->isEmpty()) {
            $this->pack = false;

            return $this->pack;
        }

        $result = $this->composedOf->filter(function (Item $item) {
            return $item->separatelySellable;
        });

        $this->pack = !$result->isEmpty();

        return $this->pack;
    }

    public function jsonSerialize(): mixed
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'label' => $this->label,
            'price' => $this->price,
            'colour' => $this->colour,
            'stock' => $this->stock,
            'ticket' => $this->ticket,
            'pack' => $this->isPack(),
        ];
    }
}
