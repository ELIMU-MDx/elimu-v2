<?php

declare(strict_types=1);

namespace Domain\Experiment\DataTransferObjects;

use Spatie\DataTransferObject\DataTransferObject;

final class ResultCalculationParameter extends DataTransferObject
{
    public float $cutoff;

    public ?float $intercept = null;

    public ?float $slope = null;

    public function shouldQuantify(): bool
    {
        return $this->slope !== null && $this->intercept !== null;
    }
}
