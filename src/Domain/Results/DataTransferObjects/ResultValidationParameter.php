<?php

declare(strict_types=1);

namespace Domain\Results\DataTransferObjects;

use App\Models\AssayParameter;
use Support\Data;

final class ResultValidationParameter extends Data
{
    public function __construct(
        public int $requiredRepetitions,
        public float $cutoff,
        public ?float $standardDeviationCutoff = null,
        public ?float $coefficientOfVariationCutoff = null,
        public string|float|null $positiveControl = null,
        public string|float|null $negativeControl = null,
        public string|float|null $ntcControl = null,
    ) {

    }

    public static function fromModel(AssayParameter $parameter): ResultValidationParameter
    {
        return new ResultValidationParameter(
            requiredRepetitions: $parameter->required_repetitions,
            cutoff: $parameter->cutoff,
            standardDeviationCutoff: $parameter->standard_deviation_cutoff,
            coefficientOfVariationCutoff: $parameter->coefficient_of_variation_cutoff,
            positiveControl: $parameter->positive_control,
            negativeControl: $parameter->negative_control,
            ntcControl: $parameter->ntc_control,
        );
    }
}
