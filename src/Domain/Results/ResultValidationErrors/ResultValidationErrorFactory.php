<?php

declare(strict_types=1);

namespace Domain\Results\ResultValidationErrors;

use BadMethodCallException;
use Illuminate\Support\Collection;

final class ResultValidationErrorFactory
{
    public static function get(
        string $identifier,
    ): ResultValidationError {
        return match ($identifier) {
            NotEnoughRepetitionsError::IDENTIFIER => new NotEnoughRepetitionsError(),
            DivergingMeasurementsError::IDENTIFIER => new DivergingMeasurementsError(),
            StandardDeviationExceedsCutoffError::IDENTIFIER => new StandardDeviationExceedsCutoffError(),
            ControlValidationError::IDENTIFIER => new ControlValidationError(),
            default => throw new BadMethodCallException('No error class found for '.$identifier),
        };
    }

    /**
     * @return \Illuminate\Support\Collection<\Domain\Results\ResultValidationErrors\ResultValidationError>
     */
    public static function all(): Collection
    {
        return Collection::make([
            new NotEnoughRepetitionsError(),
            new DivergingMeasurementsError(),
            new StandardDeviationExceedsCutoffError(),
            new ControlValidationError(),
        ]);
    }
}
