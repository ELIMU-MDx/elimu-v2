<?php

namespace Support\ValueObjects;

use Support\Data;

final class Percentage extends Data
{
    public function __construct(readonly public float $value, readonly public int $precision = 2)
    {
    }

    public function __toString(): string
    {
        return round($this->value * 100, $this->precision).'%';
    }
}
