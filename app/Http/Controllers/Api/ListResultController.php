<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Models\Assay;
use App\Models\Result;
use App\Models\Sample;
use Domain\Rdml\Enums\MeasurementType;
use Illuminate\Support\Facades\Auth;

final class ListResultController
{
    public function __invoke(Assay $assay)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (! $user->studies()->where('studies.id', $assay->study_id)->exists()) {
            return [];
        }

        return Sample::with([
            'results' => fn ($query) => $query->withCount('measurements'),
            'results.resultErrors',
        ])
            ->whereHas('results', fn ($query) => $query->where('assay_id', $assay->id))
            ->whereHas('results.measurements', fn ($query) => $query->where('type', MeasurementType::SAMPLE()))
            ->get()
            ->map(function (Sample $sample) {
                return $sample->results
                    ->flatMap(function (Result $result) {
                        return [
                            'replicas_'.strtolower($result->target) => $result->measurements->included()->count(),
                            'replicas_'.strtolower($result->target).'_total' => $result->measurements->count(),
                            'mean_cq_'.strtolower($result->target) => $this->parseFloat($result->cq),
                            'standard_deviation_cq_'.strtolower($result->target) => $this->parseFloat($result->standard_deviation),
                            'qualitative_result_'.strtolower($result->target) => $result->resultErrors->isEmpty()
                                ? $result->qualification
                                : $result->resultErrors->pluck('error')->join("\n"),
                            'quantitative_result_'.strtolower($result->target) => $result->resultErrors->isEmpty() ? $this->parseFloat($result->quantification) : '',
                        ];
                    })
                    ->prepend($sample->identifier, 'sample')
                    ->toArray();
            })->values();
    }

    private function parseFloat(?string $value): ?float
    {
        return $value !== null ? (float) $value : null;
    }
}
