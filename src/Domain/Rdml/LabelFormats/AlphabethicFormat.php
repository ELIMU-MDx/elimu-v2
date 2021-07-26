<?php

declare(strict_types=1);

namespace Domain\Rdml\LabelFormats;

final class AlphabethicFormat implements LabelFormat
{
    public const ALPHABET = [
        'a',
        'b',
        'c',
        'd',
        'e',
        'f',
        'g',
        'h',
        'i',
        'j',
        'k',
        'l',
        'm',
        'n',
        'o',
        'p',
        'q',
        'r',
        's',
        't',
        'u',
        'v',
        'w',
        'x',
        'y',
        'z',
    ];

    public function getPosition(int $position): string
    {
        return strtoupper(self::ALPHABET[$position - 1]);
    }
}
