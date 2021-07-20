<?php

declare(strict_types=1);

namespace Domain\Evaluation\Validators;

use Domain\Evaluation\Collections\DataPointCollection;
use Domain\Evaluation\DataTransferObjects\SampleValidationParameter;

final class SampleValidator
{
    /**
     * @param  \Domain\Evaluation\Collections\DataPointCollection  $dataPoints
     * @return string[] error messages
     */
    public function validate(DataPointCollection $dataPoints, SampleValidationParameter $validationParameter): array
    {
        $errors = [];

        $numberOfResults = $dataPoints->count();
        $numberOfPositiveResults = $dataPoints->positives($validationParameter->cutoff)->count();

        if ($numberOfResults < $validationParameter->requiredRepetitions) {
            $errors[] = "Only {$numberOfResults} repetitions instead of {$validationParameter->requiredRepetitions}";
        }

        if ($numberOfPositiveResults !== $numberOfResults) {
            $errors[] = "Needs repetition since not all messurements evaluate to the same result";
        }

        if ($numberOfPositiveResults > 0 && $numberOfResults > 1 && $dataPoints->standardDeviationCq()->raw() > $validationParameter->standardDeviationCutoff) {
            $errors[] = "Standard deviation for the data points exceeds the defined cutoff";
        }

        return $errors;
    }
}
