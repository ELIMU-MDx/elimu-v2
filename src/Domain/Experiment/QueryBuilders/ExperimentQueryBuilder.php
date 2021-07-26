<?php

declare(strict_types=1);

namespace Domain\Experiment\QueryBuilders;

use Domain\Experiment\Models\Measurement;
use Domain\Rdml\Enums\MeasurementType;
use Illuminate\Database\Eloquent\Builder;

final class ExperimentQueryBuilder extends Builder
{
    public function withSamplesCount(): ExperimentQueryBuilder
    {
        return $this->addSelect([
            'count_samples' => Measurement::selectRaw('count(DISTINCT sample_id)')
                ->whereColumn('experiment_id', 'experiments.id')
                ->where('type', MeasurementType::SAMPLE()),
        ]);
    }
}
