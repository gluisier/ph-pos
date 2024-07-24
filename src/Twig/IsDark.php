<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;

class IsDark extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new \Twig\TwigFunction('is_dark', [$this, 'isDark'])
        ];
    }

    public function isDark(?string $hex): bool
    {
        if (!$hex || strlen($hex) !== 7) {
            return false;
        }
        $rgb = hexdec(substr($hex, 1));
        $r = ($rgb & 0xff0000) >> 16;
        $g = ($rgb & 0xff00) >> 8;
        $b = ($rgb & 0xff);

        return (($r * 0.299 + $g * 0.587 + $b * 0.114) / 256) < 0.114;
    }
}