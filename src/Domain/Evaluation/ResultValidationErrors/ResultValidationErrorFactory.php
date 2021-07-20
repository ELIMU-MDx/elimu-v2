<?php

declare(strict_types=1);

namespace Domain\Evaluation\ResultValidationErrors;

use BadMethodCallException;
use Illuminate\Support\Collection;

final class ResultValidationErrorFactory
{
    public static function get(
        string $identifier,
    ): ResultValidationError {
        return match ($identifier) {
            NotEnoughRepetitionsError::IDENTIFIER => new NotEnoughRepetitionsError(),
            ResultsDivergeError::IDENTIFIER => new ResultsDivergeError(),
            StandardDeviationExceedsCutoffError::IDENTIFIER => new StandardDeviationExceedsCutoffError(),
            default => throw new BadMethodCallException('No error class found for '.$identifier),
        };
    }

    /**
     * @return \Illuminate\Support\Collection<\Domain\Evaluation\ResultValidationErrors\ResultValidationError>
     */
    public static function all(): Collection
    {
        return Collection::make([
            new NotEnoughRepetitionsError(),
            new ResultsDivergeError(),
            new StandardDeviationExceedsCutoffError(),
        ]);
    }
}
