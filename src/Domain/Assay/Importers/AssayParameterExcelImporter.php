<?php

declare(strict_types=1);

namespace Domain\Assay\Importers;

use Domain\Assay\Models\AssayParameter;
use Domain\Assay\Rules\ControlParameterValidationRule;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Support\DecimalValidationRule;

final class AssayParameterExcelImporter implements ToModel, WithValidation, WithHeadingRow
{
    public function __construct(private int $assayId)
    {
    }

    public function model(array $row): AssayParameter
    {
        return new AssayParameter([
            'assay_id' => $this->assayId,
            'target' => $row['target'],
            'cutoff' => $row['cutoff'],
            'standard_deviation_cutoff' => $row['cutoff_standard_deviation'],
            'slope' => $row['slope'] ?: null,
            'intercept' => $row['intercept'] ?: null,
            'required_repetitions' => $row['required_repetitions'],
            'positive_control' => $row['positive_control'],
            'negative_control' => $row['negative_control'],
            'ntc_control' => $row['ntc_control'],
        ]);
    }

    public function rules(): array
    {
        return [
            'target' => ['required', 'string', 'max:255'],
            'cutoff' => ['required', 'numeric', new DecimalValidationRule(8, 2)],
            'cutoff_standard_deviation' => ['required', 'numeric', new DecimalValidationRule(8, 2)],
            'slope' => ['nullable', 'numeric', new DecimalValidationRule(8, 2)],
            'intercept' => ['nullable', 'numeric', new DecimalValidationRule(8, 2)],
            'required_repetitions' => ['required', 'numeric', 'min:1'],
            'positive_control' => ['nullable', new ControlParameterValidationRule()],
            'negative_control' => ['nullable', new ControlParameterValidationRule()],
            'ntc_control' => ['nullable', new ControlParameterValidationRule()],

        ];
    }
}
