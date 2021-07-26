<?php

declare(strict_types=1);

namespace Domain\Results\ResultValidationErrors;

use Domain\Rdml\Enums\MeasurementType;
use Domain\Results\DataTransferObjects\Result;
use Domain\Results\DataTransferObjects\ResultValidationParameter;

final class DivergingMeasurementsError implements ResultValidationError
{
    public const IDENTIFIER = 'results-diverge';

    public function message(Result $result, ResultValidationParameter $parameter): string
    {
        return 'Needs repetition since not all messurements evaluate to the same result';
    }

    public function validate(Result $result, ResultValidationParameter $parameter): bool
    {
        $numberOfPositives = $result->measurements->included()->positives($parameter->cutoff)->count();

        return $numberOfPositives === 0 || $numberOfPositives === $result->repetitions;
    }

    public function appliesFor(Result $result): bool
    {
        return $result->type === MeasurementType::SAMPLE();
    }
}
