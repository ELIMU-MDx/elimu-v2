<?php

declare(strict_types=1);

namespace Domain\Results\DataTransferObjects;

use App\Models\AssayParameter;
use Spatie\DataTransferObject\DataTransferObject;

final class ResultValidationParameter extends DataTransferObject
{
    public int $requiredRepetitions;

    public ?float $standardDeviationCutoff = null;

    public ?float $coefficientOfVariationCutoff = null;

    public float $cutoff;

    public string|float|null $positiveControl = null;

    public string|float|null $negativeControl = null;

    public string|float|null $ntcControl = null;

    public static function fromModel(AssayParameter $parameter): ResultValidationParameter
    {
        return new ResultValidationParameter([
            'requiredRepetitions' => $parameter->required_repetitions,
            'standardDeviationCutoff' => $parameter->standard_deviation_cutoff,
            'coefficientOfVariationCutoff' => $parameter->coefficient_of_variation_cutoff,
            'cutoff' => $parameter->cutoff,
            'positiveControl' => $parameter->positive_control,
            'negativeControl' => $parameter->negative_control,
            'ntcControl' => $parameter->ntc_control,
        ]);
    }
}
