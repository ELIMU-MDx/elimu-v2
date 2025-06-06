<?php

declare(strict_types=1);

namespace Domain\Experiment\QueryBuilders;

use App\Models\Measurement;
use Domain\Rdml\Enums\MeasurementType;
use Illuminate\Database\Eloquent\Builder;

/**
 * @template TModelClass of \Illuminate\Database\Eloquent\Model
 *
 * @extends Builder<TModelClass>
 */
final class ExperimentQueryBuilder extends Builder
{
    public function withSamplesCount(): ExperimentQueryBuilder
    {
        return $this->addSelect([
            'count_samples' => Measurement::selectRaw('count(DISTINCT sample_id)')
                ->whereColumn('experiment_id', 'experiments.id')
                ->where('type', MeasurementType::SAMPLE),
        ]);
    }
}
