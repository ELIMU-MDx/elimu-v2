<?php

declare(strict_types=1);

namespace Domain\Experiment\QueryBuilders;

use Auth;
use Domain\Rdml\Enums\MeasurementType;
use Domain\Results\Enums\QualitativeResult;
use Illuminate\Database\Eloquent\Builder;
use Str;

final class SampleQueryBuilder extends Builder
{
    public function countAll(int $assayId, int $studyId): SampleQueryBuilder
    {
        return $this->whereHas('results.measurements', function (Builder $query) use ($assayId) {
            return $query->where('assay_id', $assayId)
                ->where('type', MeasurementType::SAMPLE());
        })->where('study_id', $studyId);
    }

    public function listAll(int $assayId, int $studyId): SampleQueryBuilder
    {
        return $this->with(
            [
                'results.assay.parameters' => function ($query) use ($assayId) {
                    return $query->where('assay_id', $assayId);
                },
                'results.resultErrors',
                'results.measurements' => function ($query) {
                    return $query->orderBy('target')
                        ->where('type', MeasurementType::SAMPLE());
                },
            ]
        )
            ->where('study_id', Auth::user()->study_id)
            ->whereHas('results.measurements', function ($query) {
                return $query->orderBy('target')
                    ->where('type', MeasurementType::SAMPLE());
            })
            ->orderBy('identifier');
    }

    public function searchBySampleIdentifier(string $search): SampleQueryBuilder
    {
        if (!trim($search)) {
            return $this;
        }

        return $this->where(
            'identifier',
            'LIKE',
            (string) Str::of($search)->replace(['%', '_'], ['\%', '\_'])->append('%')
        );
    }

    public function filterByResult(string $filter): SampleQueryBuilder
    {
        return match ($filter) {
            'all' => $this,
            'valid' => $this->whereDoesntHave('results.resultErrors'),
            'invalid' => $this->whereHas('results.resultErrors'),
            'positive' => $this->whereHas('results', function (Builder $query) {
                return $query->where('qualification', QualitativeResult::POSITIVE());
            }),
            'negative' => $this->whereHas(
                'results',
                function (Builder $query) {
                    return $query->where('qualification', QualitativeResult::NEGATIVE());
                }
            ),
            default => $this,
        };
    }
}
