<?php

namespace Domain\Results\ResultValidationErrors;

use Domain\Rdml\Enums\MeasurementType;
use Domain\Results\DataTransferObjects\Result;
use Domain\Results\DataTransferObjects\ResultValidationParameter;
use Support\ValueObjects\RoundedNumber;

final class CoefficientOfVariationExceedsCutoffError implements ResultValidationError
{
    public const IDENTIFIER = 'coefficient-of-variation-exceeds-cutoff';

    public function message(Result $result, ResultValidationParameter $parameter): string
    {
        $expectedPercentage = (new RoundedNumber($parameter->coefficientOfVariationCutoff))->toPercentage();

        return "Coefficient of variation of '{$result->measurements->included()->coefficientOfVariation()->toPercentage()}' exceeds cutoff of '{$expectedPercentage}'";
    }

    public function validate(Result $result, ResultValidationParameter $parameter): bool
    {
        if ($parameter->coefficientOfVariationCutoff === null) {
            return true;
        }

        if ($result->repetitions <= 1) {
            return true;
        }

        if (! $result->averageCQ->raw()) {
            return true;
        }

        return $result->measurements->included()->coefficientOfVariation()->raw() <= $parameter->coefficientOfVariationCutoff;
    }

    public function appliesFor(Result $result): bool
    {
        return $result->type === MeasurementType::SAMPLE;
    }
}
