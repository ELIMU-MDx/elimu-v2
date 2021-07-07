<?php

declare(strict_types=1);

namespace Domain\Evaluation\DataTransferObjects;

use Spatie\DataTransferObject\DataTransferObject;

final class SampleValidationParameter extends DataTransferObject
{
    public int $requiredRepetitions;

    public float $standardDeviationCutoff;

    public float $cutoff;
}
