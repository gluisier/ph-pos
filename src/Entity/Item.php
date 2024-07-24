<?php

namespace App\Entity;

use App\Repository\ItemRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ItemRepository::class)]
class Item implements \JsonSerializable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(length: 7)]
    private ?string $label = null;

    #[ORM\Column(type: Types::SMALLINT, nullable: true)]
    private ?int $price = null;

    #[ORM\Column(length: 7, nullable: true)]
    private ?string $colour = null;

    #[ORM\Column(nullable: true)]
    private ?int $quantity = null;

    #[ORM\Column]
    private ?bool $ticket = null;

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'subItems')]
    private ?self $packedIn = null;

    #[ORM\OneToMany(mappedBy: 'packedIn', targetEntity: self::class)]
    private Collection $subItems;

    #[ORM\OneToMany(mappedBy: 'item', targetEntity: OrderLine::class)]
    private Collection $orders;

    public function __construct()
    {
        $this->subItems = new ArrayCollection();
        $this->orders = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): static
    {
        $this->label = $label;

        return $this;
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

    public function getColour(): ?string
    {
        return $this->colour;
    }

    public function setColour(?string $colour): static
    {
        $this->colour = $colour;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(?int $quantity): static
    {
        $this->quantity = $quantity;

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

    public function getPackedIn(): ?self
    {
        return $this->packedIn;
    }

    public function setPackedIn(?self $packedIn): static
    {
        $this->packedIn = $packedIn;

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getSubItems(): Collection
    {
        return $this->subItems;
    }

    public function addSubItem(self $subItem): static
    {
        if (!$this->subItems->contains($subItem)) {
            $this->subItems->add($subItem);
            $subItem->setPackedIn($this);
        }

        return $this;
    }

    public function removeSubItem(self $subItem): static
    {
        if ($this->subItems->removeElement($subItem)) {
            // set the owning side to null (unless already changed)
            if ($subItem->getPackedIn() === $this) {
                $subItem->setPackedIn(null);
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

    public function jsonSerialize(): mixed
    {
        return [
            'title' => $this->title,
            'label' => $this->label,
            'price' => $this->price,
            'colour' => $this->colour,
            'quantity' => $this->quantity,
            'ticket' => $this->ticket,
        ];
    }
}
