<?php

declare(strict_types=1);

namespace Domain\Evaluation\ResultValidationErrors;

use Domain\Evaluation\Collections\DataPointCollection;
use Domain\Evaluation\DataTransferObjects\SampleValidationParameter;

final class ResultsDivergeError implements ResultValidationError
{
    public const IDENTIFIER = 'results-diverge';

    public function message(DataPointCollection $dataPoints, SampleValidationParameter $parameter): string
    {
        return 'Needs repetition since not all messurements evaluate to the same result';
    }

    public function validate(DataPointCollection $dataPoints, SampleValidationParameter $parameter): bool
    {
        $numberOfPositives = $dataPoints->positives($parameter->cutoff)->count();

        return $numberOfPositives === 0 || $numberOfPositives === $dataPoints->count();
    }
}
