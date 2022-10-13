<?php

namespace App\Admin\Experiments\Filters;

use Domain\Experiment\Models\Measurement;

final class AlwaysTrueFilter implements MeasurementFilter
{
    public function matches(Measurement $measurement): bool
    {
        return true;
    }
}
