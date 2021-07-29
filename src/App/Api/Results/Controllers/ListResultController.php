<?php

declare(strict_types=1);

namespace App\Api\Results\Controllers;

use Domain\Assay\Models\Assay;
use Domain\Experiment\Models\Sample;
use Domain\Results\Models\Result;
use Illuminate\Contracts\Auth\StatefulGuard;

final class ListResultController
{
    public function __invoke(Assay $assay, StatefulGuard $guard)
    {
        return Sample::with([
            'results' => fn($query) => $query->withCount('measurements'),
            'results.resultErrors'
        ])
            ->whereHas('results', fn($query) => $query->where('assay_id', $assay->id))
            ->get()
            ->map(function (Sample $sample) {
                return $sample->results
                    ->flatMap(function (Result $result) {
                        return [
                            'replicas_'.strtolower($result->target) => $result->measurements_count,
                            'mean_cq_'.strtolower($result->target) => $result->cq,
                            'standard_deviation_cq_'.strtolower($result->target) => $result->standard_deviation,
                            'qualitative_result_'.strtolower($result->target) => $result->resultErrors->isEmpty()
                                ? $result->qualification
                                : $result->resultErrors->pluck('error')->join("\n"),
                            'quantitative_result_'.strtolower($result->target) => $result->resultErrors->isEmpty() ? $result->quantification : '',
                        ];
                    })
                    ->prepend($sample->identifier, 'sample')
                    ->toArray();
            })->values();
    }
}
