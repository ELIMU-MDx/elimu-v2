<?php

declare(strict_types=1);

namespace Domain\Experiment\DataTransferObjects;

use App\Models\AssayParameter;
use Spatie\DataTransferObject\DataTransferObject;

final class ResultCalculationParameter extends DataTransferObject
{
    public string $target;

    public float $cutoff;

    public ?float $intercept = null;

    public ?float $slope = null;

    public function shouldQuantify(): bool
    {
        return $this->slope !== null && $this->intercept !== null;
    }

    public static function fromModel(AssayParameter $model): ResultCalculationParameter
    {
        return new ResultCalculationParameter([
            'target' => $model->target,
            'cutoff' => $model->cutoff,
            'intercept' => $model->intercept,
            'slope' => $model->slope,
        ]);
    }
}
