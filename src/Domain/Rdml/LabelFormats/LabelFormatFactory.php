<?php

declare(strict_types=1);

namespace Domain\Rdml\LabelFormats;

final class LabelFormatFactory
{
    public static function get(string $labelFormat): LabelFormat
    {
        return match ($labelFormat) {
            '123' => new NumericFormat(),
            default => new AlphabethicFormat(),
        };
    }
}
