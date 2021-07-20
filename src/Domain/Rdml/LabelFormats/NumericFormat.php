<?php

declare(strict_types=1);

namespace Domain\Rdml\LabelFormats;

final class NumericFormat implements LabelFormat
{
    public function getPosition(int $position): string
    {
        return (string) $position;
    }
}
