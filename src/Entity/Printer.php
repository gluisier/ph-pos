<?php

namespace App\Entity;

use App\Config\Printer\API;
use App\Config\Printer\Status;
use App\Repository\PrinterRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: PrinterRepository::class)]
#[UniqueEntity(fields: ['id'], message: 'printer.id.not_unique')]
class Printer
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'NONE')]
    #[ORM\Column]
    #[Assert\NotBlank(message: 'printer.id.blank')]
    #[Assert\Regex('`^[a-z0-9_]+$`', message: 'printer.id.regex_not_match')]
    private ?string $id = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: 'printer.model.blank')]
    private ?string $model = null;

    #[ORM\Column(length: 15)]
    private ?Status $status = null;

    #[ORM\Column(length: 63)]
    #[Assert\Ip(version: Assert\Ip::ALL_ONLY_PRIVATE, message: 'printer.ip.not_private')]
    private ?string $ip = null;

    #[ORM\Column(type: Types::SMALLINT)]
    #[Assert\Range(
        min: 2,
        max: 65535,
        notInRangeMessage: 'printer.port.out_of_range',
    )]
    private ?int $port = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotNull(message: 'printer.api.null')]
    private ?API $api = null;

    #[ORM\ManyToOne(inversedBy: 'printers')]
    private ?Location $location = null;

    /**
     * @var Collection<int, User>
     */
    #[ORM\OneToMany(mappedBy: 'printer', targetEntity: User::class)]
    private Collection $users;

    public function __construct()
    {
        $this->users = new ArrayCollection();
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(string $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getModel(): ?string
    {
        return $this->model;
    }

    public function setModel(string $model): static
    {
        $this->model = $model;

        return $this;
    }

    public function getStatus(): ?Status
    {
        return $this->status;
    }

    public function setStatus(Status $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getIp(): ?string
    {
        return $this->ip;
    }

    public function setIp(string $ip): static
    {
        $this->ip = $ip;

        return $this;
    }

    public function getPort(): ?int
    {
        return $this->port;
    }

    public function setPort(int $port): static
    {
        $this->port = $port;

        return $this;
    }

    public function getAPI(): ?API
    {
        return $this->api;
    }

    public function setAPI(API $api): static
    {
        $this->api = $api;

        return $this;
    }

    public function getLocation(): ?Location
    {
        return $this->location;
    }

    public function setLocation(?Location $location): static
    {
        $this->location = $location;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): static
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
            $user->setPrinter($this);
        }

        return $this;
    }

    public function removeUser(User $user): static
    {
        if ($this->users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getPrinter() === $this) {
                $user->setPrinter(null);
            }
        }

        return $this;
    }
}
