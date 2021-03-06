<?php

declare(strict_types=1);

namespace Domain\Results\DataTransferObjects;

use Domain\Assay\Models\AssayParameter;
use Spatie\DataTransferObject\DataTransferObject;

final class ResultValidationParameter extends DataTransferObject
{
    public int $requiredRepetitions;

    public float $standardDeviationCutoff;

    public float $cutoff;

    public string | float | null $positiveControl;

    public string | float | null $negativeControl;

    public string | float | null $ntcControl;

    public static function fromModel(AssayParameter $parameter): ResultValidationParameter
    {
        return new ResultValidationParameter([
            'requiredRepetitions' => $parameter->required_repetitions,
            'standardDeviationCutoff' => $parameter->standard_deviation_cutoff,
            'cutoff' => $parameter->cutoff,
            'positiveControl' => $parameter->positive_control,
            'negativeControl' => $parameter->negative_control,
            'ntcControl' => $parameter->ntc_control,
        ]);
    }
}
