<?php

namespace App\Twig;

use Doctrine\Common\Collections\Collection;
use Twig\Extension\AbstractExtension;

class Gradient extends AbstractExtension
{
    const STEP_WIDTH = 2;

    const STEP_WIDTH_UNIT = 'rem';

    public function getFunctions(): array
    {
        return [
            new \Twig\TwigFunction('variants_gradient', [$this, 'variantsGradient']),
            new \Twig\TwigFunction('pack_gradient', [$this, 'packGradient'])
        ];
    }

    /**
     * @param Collection<int, Item> $items
     */
    public function variantsGradient(Collection $items): ?string
    {
        $colours = $this->getColours($items);

        if (count($colours) < 2) {
            return null;
        }

        $steps = [];
        foreach ($colours as $i => $colour) {
            $steps[] = implode(' ', [$colour, $i * self::STEP_WIDTH . self::STEP_WIDTH_UNIT, ($i + 1) * self::STEP_WIDTH . self::STEP_WIDTH_UNIT]);
        }

        return 'background: repeating-linear-gradient(45deg, ' . implode(', ', $steps) . ')';
    }

    /**
     * @param Collection<int, Item> $items
     */
    public function packGradient(Collection $items): ?string
    {
        $colours = $this->getColours($items);
        if (count($colours) < 2) {
            return null;
        }

        $steps = [];
        foreach ($colours as $i => $colour) {
            $steps[] = implode(' ', [$colour, $i * self::STEP_WIDTH . self::STEP_WIDTH_UNIT]);
        }
        $steps[] = implode(' ', [$colours[0], count($colours) * self::STEP_WIDTH . self::STEP_WIDTH_UNIT]);

        return 'background: repeating-linear-gradient(-45deg, ' . implode(', ', $steps) . ')';
    }

    /**
     * @param Collection<int, Item> $items
     */
    private function getColours(Collection $items): array
    {
        $colours = [];
        foreach ($items as $item) {
            $colours[] = $item->getColour();
        }

        return array_values(array_unique($colours));
    }
}