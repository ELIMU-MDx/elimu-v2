<?php

declare(strict_types=1);

namespace Support;

final class RoundedNumber
{
    public function __construct(private ?float $number, private int $precision = 2)
    {
    }

    public function __toString(): string
    {
        return $this->toString();
    }

    public static function make(?float $number, int $precision = 2): RoundedNumber
    {
        return new RoundedNumber($number, $precision);
    }

    public function raw(): ?float
    {
        return $this->number;
    }

    public function rounded(): ?float
    {
        return $this->number === null ? null : round($this->number, $this->precision);
    }

    public function toString(): string
    {
        if ($this->number === null) {
            return '';
        }

        return number_format(round($this->number, $this->precision), $this->precision);
    }

    public function toPercentage(): string
    {
        if ($this->number === null) {
            return '';
        }

        return number_format(round($this->number * 100, $this->precision), $this->precision) . '%';
    }
}
