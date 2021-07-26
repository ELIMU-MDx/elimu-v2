<?php

declare(strict_types=1);

namespace Domain\Results\ResultValidationErrors;

use Domain\Rdml\Enums\MeasurementType;
use Domain\Results\DataTransferObjects\Result;
use Domain\Results\DataTransferObjects\ResultValidationParameter;

final class NotEnoughRepetitionsError implements ResultValidationError
{
    public const IDENTIFIER = 'not-enough-repetitions';

    public function message(Result $result, ResultValidationParameter $parameter): string
    {
        return "Only {$result->repetitions} repetitions instead of {$parameter->requiredRepetitions}";
    }

    public function validate(Result $result, ResultValidationParameter $parameter): bool
    {
        return $result->repetitions >= $parameter->requiredRepetitions;
    }

    public function appliesFor(Result $result): bool
    {
        return $result->type === MeasurementType::SAMPLE();
    }
}
