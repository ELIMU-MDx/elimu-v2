<?php

namespace Domain\Assay\Exporters;

use Domain\Assay\Models\AssayParameter;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

final class AssayExcelExporter implements WithHeadings, FromQuery, WithMapping
{
    public function __construct(private int $assayId)
    {
    }

    public function headings(): array
    {
        return [
            'target',
            'description',
            'is_control',
            'cutoff',
            'cutoff_standard_deviation',
            'coefficient_of_variation_cutoff',
            'slope',
            'intercept',
            'required_repetitions',
            'positive_control',
            'negative_control',
            'ntc_control',
        ];
    }

    public function query()
    {
        return AssayParameter::where('assay_id', $this->assayId);
    }

    /**
     * @param  AssayParameter  $row
     * @return array
     */
    public function map($row): array
    {
        return [
            $row->target,
            $row->description,
            $row->is_control,
            $row->cutoff,
            $row->standard_deviation_cutoff,
            $row->coefficient_of_variation_cutoff,
            $row->slope,
            $row->intercept,
            $row->required_repetitions,
            $row->positive_control,
            $row->negative_control,
            $row->ntc_control,
        ];
    }
}
