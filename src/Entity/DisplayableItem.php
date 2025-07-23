<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\MappedSuperclass;
use Symfony\Component\Validator\Constraints as Assert;

#[MappedSuperclass]
abstract class DisplayableItem
{
    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'displayable_item.title.blank')]
    #[Assert\NoSuspiciousCharacters(
        restrictionLevelMessage: 'displayable_item.title.contains.restrictions_breaks',
        invisibleMessage: 'displayable_item.title.contains.invisibles',
        mixedNumbersMessage: 'displayable_item.title.contains.mixed_numbers',
        hiddenOverlayMessage: 'displayable_item.title.contains.hidden_overlay_characters',
        checks:
            Assert\NoSuspiciousCharacters::CHECK_INVISIBLE
            | Assert\NoSuspiciousCharacters::CHECK_MIXED_NUMBERS
            | Assert\NoSuspiciousCharacters::CHECK_HIDDEN_OVERLAY
        )]
    protected ?string $title = null;

    #[ORM\Column(length: 7)]
    #[Assert\NotBlank(message: 'displayable_item.label.blank')]
    #[Assert\Length(max: 7)]
    protected ?string $label = null;

    #[ORM\Column(length: 7, nullable: true)]
    #[Assert\CssColor(formats: Assert\CssColor::HEX_LONG, message: 'displayable_item.colour.not_hex_long')]
    protected ?string $colour = null;

    #[ORM\Column]
    private ?bool $public = null;

    #[ORM\Column(type: Types::SMALLINT, nullable: true)]
    private ?int $position = null;


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

    public function getColour(): ?string
    {
        return $this->colour;
    }

    public function setColour(?string $colour): static
    {
        $this->colour = $colour;

        return $this;
    }

    public function isPublic(): ?bool
    {
        return $this->public;
    }

    public function setPublic(bool $public): static
    {
        $this->public = $public;

        return $this;
    }

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function setPosition(int $position): static
    {
        $this->position = $position;

        return $this;
    }
}
