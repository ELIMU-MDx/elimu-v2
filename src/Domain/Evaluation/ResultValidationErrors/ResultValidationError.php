<?php

declare(strict_types=1);

namespace Domain\Evaluation\ResultValidationErrors;

use Domain\Evaluation\Collections\DataPointCollection;
use Domain\Evaluation\DataTransferObjects\SampleValidationParameter;

interface ResultValidationError
{
    public function message(DataPointCollection $dataPoints, SampleValidationParameter $parameter): string;

    public function validate(DataPointCollection $dataPoints, SampleValidationParameter $parameter): bool;
}
