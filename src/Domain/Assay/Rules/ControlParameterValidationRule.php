<?php

declare(strict_types=1);

namespace Domain\Assay\Rules;

use Illuminate\Contracts\Validation\Rule;

final class ControlParameterValidationRule implements Rule
{

    public function passes($attribute, $value): bool
    {
        if($value === 'null') {
            return true;
        }

        if(!is_numeric($value)) {
            return false;
        }

        return abs((float)$value) >= 0.01 && abs((float)$value) <= 999999.99;
    }

    public function message(): string
    {
        return 'The :attribute must either be null or a number between 0.01 and 999999';
    }
}
