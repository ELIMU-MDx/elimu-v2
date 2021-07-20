<?php

declare(strict_types=1);

namespace Domain\Evaluation\ResultValidationErrors;

use Domain\Evaluation\Collections\DataPointCollection;
use Domain\Evaluation\DataTransferObjects\SampleValidationParameter;

final class NotEnoughRepetitionsError implements ResultValidationError
{
    public const IDENTIFIER = 'not-enough-repetitions';

    public function message(DataPointCollection $dataPoints, SampleValidationParameter $parameter): string
    {
        return "Only {$dataPoints->count()} repetitions instead of {$parameter->requiredRepetitions}";
    }

    public function validate(DataPointCollection $dataPoints, SampleValidationParameter $parameter): bool
    {
        return $dataPoints->count() >= $parameter->requiredRepetitions;
    }
}
