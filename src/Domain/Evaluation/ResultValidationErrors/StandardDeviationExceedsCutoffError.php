<?php

declare(strict_types=1);

namespace Domain\Evaluation\ResultValidationErrors;

use Domain\Evaluation\Collections\DataPointCollection;
use Domain\Evaluation\DataTransferObjects\SampleValidationParameter;

final class StandardDeviationExceedsCutoffError implements ResultValidationError
{
    public const IDENTIFIER = 'standard-deviation-exceeds-cutoff';

    public function message(DataPointCollection $dataPoints, SampleValidationParameter $parameter): string
    {
        return "Standard deviation of '{$dataPoints->standardDeviationCq()}' exceeds cutoff of '{$parameter->standardDeviationCutoff}'";
    }

    public function validate(DataPointCollection $dataPoints, SampleValidationParameter $parameter): bool
    {
        return $dataPoints->count() <= 1 || $dataPoints->standardDeviationCq()->raw() <= $parameter->standardDeviationCutoff;
    }
}
