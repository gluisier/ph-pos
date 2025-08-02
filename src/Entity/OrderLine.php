<?php

namespace App\Entity;

use App\Repository\OrderLineRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: OrderLineRepository::class)]
#[UniqueEntity(fields: ['order', 'item'], message: 'order_line.not_unique')]
class OrderLine
{
    #[ORM\Id]
    #[ORM\ManyToOne(inversedBy: 'lines')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotNull(message: 'order_line.order.null')]
    private ?Order $order = null;

    #[ORM\Id]
    #[ORM\ManyToOne(inversedBy: 'orders')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotNull(message: 'order_line.item.null')]
    private ?Item $item = null;

    #[ORM\Column(type: Types::SMALLINT)]
    #[Assert\Type('int')]
    #[Assert\GreaterThanOrEqual(1, message: 'order_line.quantity.zero')]
    private ?int $quantity = null;

    public function __construct(Order $order, Item $item, int $quantity = 0)
    {
        $this->order = $order;
        $this->item = $item;
        $this->quantity = $quantity;
    }

    public function getOrder(): ?Order
    {
        return $this->order;
    }

    public function setOrder(?Order $order): static
    {
        $this->order = $order;

        return $this;
    }

    public function getItem(): ?Item
    {
        return $this->item;
    }

    public function setItem(?Item $item): static
    {
        $this->item = $item;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): static
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getAmount(): int
    {
        return $this->quantity * $this->item->getPrice();
    }
}
