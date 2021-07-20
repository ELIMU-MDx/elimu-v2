<?php

declare(strict_types=1);

namespace Domain\Evaluation\DataTransferObjects;

use Domain\Experiment\DataTransferObjects\ResultCalculationParameter;
use Spatie\DataTransferObject\DataTransferObject;

final class AssayParameters extends DataTransferObject
{
    public string $target;

    public SampleValidationParameter $sampleValidationParameter;

    public ResultCalculationParameter $resultCalculationParameter;
}
