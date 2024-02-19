<?php

declare(strict_types=1);

namespace Domain\Experiment\DataTransferObjects;

use App\Models\AssayParameter;
use Support\Data;

final class ResultCalculationParameter extends Data
{
    public function __construct(
        readonly public string $target,
        readonly public float $cutoff,
        readonly public ?float $intercept = null,
        readonly public ?float $slope = null,
    ) {

    }

    public function shouldQuantify(): bool
    {
        return $this->slope !== null && $this->intercept !== null;
    }

    public static function fromModel(AssayParameter $model): ResultCalculationParameter
    {
        return new ResultCalculationParameter(
            target: $model->target,
            cutoff: $model->cutoff,
            intercept: $model->intercept,
            slope: $model->slope,
        );
    }
}
