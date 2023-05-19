<?php

namespace App\Http\Filters;

use App\Models\Measurement;

final class ExperimentFilter implements MeasurementFilter
{
    /**
     * @param  int[]  $experiments
     */
    public function __construct(private array $experiments)
    {
    }

    public function matches(Measurement $measurement): bool
    {
        return in_array($measurement->experiment->name, $this->experiments, true);
    }
}
