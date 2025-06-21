<?php

namespace App\Config\Printer;

use Symfony\Contracts\Translation\TranslatableInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

enum Status: string implements TranslatableInterface
{
    case STORED = 'stored';
    case DISABLED = 'disabled';
    case OK = 'ok';
    case PRINTING = 'printing';
    case WARNING = 'warning';
    case ERROR = 'error';

    public function trans(TranslatorInterface $translator, ?string $locale = null): string
    {
        return $translator->trans('app.fields.printer.status.' . $this->value . '.label.short', locale: $locale);
    }
}