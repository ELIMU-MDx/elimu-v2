<?php

declare(strict_types=1);

namespace Support;

use Illuminate\Contracts\Validation\Rule;

final class DecimalValidationRule implements Rule
{
    private float $min;

    private float $max;

    public function __construct(int $length, int $decimals)
    {
        $this->min = $decimals === 0 ? 0 : 10 ** (int) ('-'.$decimals);
        $this->max = (10 ** ($length - $decimals)) - 1 + ((10 ** $decimals) - 1) / 10 ** $decimals;
    }

    public function passes($attribute, $value): bool
    {
        return is_numeric($value) && abs((float) $value) >= $this->min && abs((float) $value) <= $this->max;
    }

    public function message()
    {
        return "The :attribute must be an absolute value between {$this->min} and {$this->max}";
    }
}
