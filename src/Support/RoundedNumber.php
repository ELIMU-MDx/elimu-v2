<?php

declare(strict_types=1);

namespace Support;

final class RoundedNumber
{
    public function __construct(private ?float $number, private int $precision = 2)
    {
    }

    public function raw(): ?float
    {
        return $this->number;
    }

    public function __toString(): string
    {
        if ($this->number === null) {
            return '';
        }

        return number_format(round($this->number, $this->precision), $this->precision);
    }
}
