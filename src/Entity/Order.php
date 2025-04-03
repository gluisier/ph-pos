<?php

namespace App\Entity;

use App\Repository\OrderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: OrderRepository::class)]
#[ORM\Table(name: '`order`')]
class Order
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?\DateTime $createdAt = null;

    #[ORM\OneToMany(mappedBy: 'order', targetEntity: OrderLine::class, cascade: ["persist"], indexBy: 'item_id')]
    #[Assert\Count(min: 1)]
    private Collection $lines;

    #[ORM\ManyToOne(inversedBy: 'orders')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotNull]
    private ?PaymentMethod $paymentMethod = null;

    public function __construct()
    {
        $this->lines = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, OrderLine>
     */
    public function getLines(): Collection
    {
        return $this->lines;
    }

    public function getLine(int $id): ?OrderLine
    {
        if (isset($this->lines[$id])) {
            return $this->lines[$id];
        } else {
            return null;
        }
    }

    public function addLine(OrderLine $line): static
    {
        if (!isset($this->lines[$line->getItem()->getId()])) {
            $this->lines[$line->getItem()->getId()] = $line;
        }

        return $this;
    }

    public function removeLine(OrderLine $line): static
    {
        if ($this->lines->removeElement($line)) {
            // set the owning side to null (unless already changed)
            if ($line->getOrder() === $this) {
                $line->setOrder(null);
            }
        }

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getPaymentMethod(): ?PaymentMethod
    {
        return $this->paymentMethod;
    }

    public function setPaymentMethod(?PaymentMethod $paymentMethod): static
    {
        $this->paymentMethod = $paymentMethod;

        return $this;
    }
}
