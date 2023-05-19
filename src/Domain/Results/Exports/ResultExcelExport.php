<?php

declare(strict_types=1);

namespace Domain\Results\Exports;

use App\Models\Assay;
use App\Models\AssayParameter;
use App\Models\Result;
use App\Models\Sample;
use App\Models\User;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithProperties;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;

final class ResultExcelExport implements FromQuery, WithMapping, WithStrictNullComparison, WithProperties, ShouldAutoSize, WithHeadings
{
    public function __construct(private Assay $assay, private User $user)
    {
    }

    public function query()
    {
        return Sample::listAll($this->assay->id, $this->user->study_id);
    }

    /**
     * @param  Sample  $sample
     * @return array
     */
    public function map($sample): array
    {
        return $sample->results
            ->flatMap(function (Result $result) {
                return [
                    'replicas_'.$result->target => $result->measurements->count(),
                    'mean_cq_'.$result->target => $result->cq,
                    'standard_deviation_cq_'.$result->target => $result->standard_deviation,
                    'qualitative_result_'.$result->target => $result->resultErrors->isEmpty()
                        ? $result->qualification
                        : $result->resultErrors->pluck('error')->join("\n"),
                    'quantitative_result_'.$result->target => $result->resultErrors->isEmpty() ? $result->quantification : '',
                ];
            })
            ->prepend($sample->identifier)
            ->toArray();
    }

    public function properties(): array
    {
        return [
            'creator' => $this->user->name,
            'lastModifiedBy' => $this->user->name,
            'title' => $this->assay->name.' Result Export',
            'description' => 'Elimu results export',
            'subject' => 'Result export',
            'keywords' => 'elimu,qpcr,result,rdml,'.$this->assay->name,
            'category' => 'Elimu',
            'manager' => $this->user->name,
            'company' => 'Elimu',
        ];
    }

    public function headings(): array
    {
        return $this->assay
            ->parameters()
            ->orderBy('target')
            ->get(['id', 'target'])
            ->flatMap(function (AssayParameter $parameter) {
                return [
                    'replicas_'.$parameter->target,
                    'mean_cq_'.$parameter->target,
                    'standard_deviation_cq_'.$parameter->target,
                    'qualitative_result_'.$parameter->target,
                    'quantitative_result_'.$parameter->target,
                ];
            })
            ->prepend('id')
            ->toArray();
    }
}
