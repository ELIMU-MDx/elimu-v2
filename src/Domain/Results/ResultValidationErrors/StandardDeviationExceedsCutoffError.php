<?php

declare(strict_types=1);

namespace Domain\Results\ResultValidationErrors;

use Domain\Rdml\Enums\MeasurementType;
use Domain\Results\DataTransferObjects\Result;
use Domain\Results\DataTransferObjects\ResultValidationParameter;

final class StandardDeviationExceedsCutoffError implements ResultValidationError
{
    public const IDENTIFIER = 'standard-deviation-exceeds-cutoff';

    public function message(Result $result, ResultValidationParameter $parameter): string
    {
        return "Standard deviation of '{$result->measurements->included()->standardDeviationCq()->toString()}' exceeds cutoff of '{$parameter->standardDeviationCutoff}'";
    }

    public function validate(Result $result, ResultValidationParameter $parameter): bool
    {
        if ($parameter->standardDeviationCutoff === null) {
            return true;
        }

        if ($result->repetitions <= 1) {
            return true;
        }

        return $result->measurements->included()->standardDeviationCq()->raw() <= $parameter->standardDeviationCutoff;
    }

    public function appliesFor(Result $result): bool
    {
        return $result->type === MeasurementType::SAMPLE;
    }
}
