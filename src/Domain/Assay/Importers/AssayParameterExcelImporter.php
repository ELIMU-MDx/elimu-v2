<?php

declare(strict_types=1);

namespace Domain\Assay\Importers;

use Domain\Assay\Models\AssayParameter;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

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
        ]);
    }

    public function rules(): array
    {
        return [
            'target' => 'required|string|max:255',
            'cutoff' => 'required|numeric',
            'cutoff_standard_deviation' => 'required|numeric',
            'slope' => 'nullable|numeric',
            'intercept' => 'nullable|numeric',
            'required_repetitions' => 'required|numeric|min:1',
        ];
    }
}
