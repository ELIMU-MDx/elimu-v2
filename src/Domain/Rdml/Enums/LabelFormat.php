<?php

declare(strict_types=1);

namespace Domain\Rdml\Enums;

use BadMethodCallException;
use Spatie\Enum\Laravel\Enum;

/**
 * @method static self NUMBERS()
 * @method static self ABC()
 * @method static self A1a1()
 */
final class LabelFormat extends Enum
{
    public static function create(string $label): LabelFormat
    {
        return match ($label) {
            'ABC' => self::ABC(),
            'A1a1' => self::A1a1(),
            '123' => self::NUMBERS(),
            default => throw new BadMethodCallException("Label '{$label}' not valid")
        };
    }
}
