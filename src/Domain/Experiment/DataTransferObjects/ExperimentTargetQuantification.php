<?php

namespace Domain\Experiment\DataTransferObjects;

use Support\Data;
use Support\ValueObjects\Percentage;
use Support\ValueObjects\RoundedNumber;

final class ExperimentTargetQuantification extends Data
{
    public function __construct(
        readonly public string $formula,
        readonly public Percentage $e,
        readonly public ?RoundedNumber $squareCorrelationCoefficient = null,
    ) {
    }
}
