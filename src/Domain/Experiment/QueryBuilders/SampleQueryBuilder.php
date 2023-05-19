<?php

declare(strict_types=1);

namespace Domain\Experiment\QueryBuilders;

use Domain\Rdml\Enums\MeasurementType;
use Domain\Results\Enums\QualitativeResult;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

/**
 * @template TModelClass of \Illuminate\Database\Eloquent\Model
 *
 * @extends Builder<TModelClass>
 */
final class SampleQueryBuilder extends Builder
{
    public function countAll(int $assayId, int $studyId): SampleQueryBuilder
    {
        return $this->whereHas('results.measurements', fn (Builder $query) => $query->where('assay_id', $assayId)
            ->where('type', MeasurementType::SAMPLE))->where('study_id', $studyId);
    }

    public function listAll(int $assayId, int $studyId): SampleQueryBuilder
    {
        return $this->with(
            [
                'results.assay.parameters' => fn ($query) => $query->where('assay_id', $assayId),
                'results.resultErrors',
                'results.measurements' => fn ($query) => $query->orderBy('target')
                    ->where('type', MeasurementType::SAMPLE),
            ]
        )
            ->where('study_id', $studyId)
            ->whereHas('results.measurements', fn ($query) => $query->where('type', MeasurementType::SAMPLE))
            ->whereHas('results', fn ($query) => $query->where('assay_id', $assayId))
            ->orderBy('identifier');
    }

    public function searchBySampleIdentifier(string $search): SampleQueryBuilder
    {
        if (! trim($search)) {
            return $this;
        }

        return $this->where(
            'identifier',
            'LIKE',
            (string) Str::of($search)->replace(['%', '_'], ['\%', '\_'])->append('%')
        );
    }

    public function filterByResult(string $filter, string $target): SampleQueryBuilder
    {
        return match ($filter) {
            'all' => $this,
            'valid' => $this->whereDoesntHave('results.resultErrors'),
            'invalid' => $this->whereHas('results.resultErrors'),
            'positive' => $this->whereHas('results', fn (Builder $query) => $query->where('qualification', QualitativeResult::POSITIVE)
                ->whereDoesntHave('resultErrors')
                ->when($target !== 'all', fn ($query) => $query->where('target', $target))),
            'negative' => $this->whereHas(
                'results',
                fn (Builder $query) => $query->where('qualification', QualitativeResult::NEGATIVE)
                    ->whereDoesntHave('resultErrors')
                    ->when($target !== 'all', fn ($query) => $query->where('target', $target))
            ),
            default => $this,
        };
    }
}
