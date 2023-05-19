<?php

declare(strict_types=1);

namespace Domain\Results\ResultValidationErrors;

use Domain\Rdml\Enums\MeasurementType;
use Domain\Results\DataTransferObjects\Result;
use Domain\Results\DataTransferObjects\ResultValidationParameter;
use Domain\Results\Enums\QualitativeResult;
use Support\Math;

final class ControlValidationError implements ResultValidationError
{
    public const IDENTIFIER = 'control-validation-error';

    public function message(Result $result, ResultValidationParameter $parameter): string
    {
        return "{$result->type->value} is invalid";
    }

    public function validate(Result $result, ResultValidationParameter $parameter): bool
    {
        $validationParameter = $this->getValidationParameter($result->type, $parameter);
        if ($validationParameter === null) {
            return true;
        }

        $validationParameter = is_numeric($validationParameter) ? (float) $validationParameter : strtolower($validationParameter);

        return match ($validationParameter) {
            'null' => $result->averageCQ->raw() === null || $result->averageCQ->raw() >= $parameter->cutoff,
            'cutoff' => Math::qualifyCq(
                $result->averageCQ->raw(),
                $parameter->cutoff
            ) === QualitativeResult::POSITIVE,
            default => $result->averageCQ->raw() !== null && $result->averageCQ->raw() <= $validationParameter,
        };
    }

    public function appliesFor(Result $result): bool
    {
        return in_array(
            $result->type,
            [MeasurementType::NTC_CONTROL, MeasurementType::NEGATIVE_CONTROL, MeasurementType::POSTIVE_CONTROL],
            true
        );
    }

    private function getValidationParameter(
        MeasurementType $type,
        ResultValidationParameter $parameter
    ): string|float|null {
        return match ($type) {
            MeasurementType::NTC_CONTROL => $parameter->ntcControl,
            MeasurementType::POSTIVE_CONTROL => $parameter->positiveControl,
            MeasurementType::NEGATIVE_CONTROL => $parameter->negativeControl,
        };
    }
}
