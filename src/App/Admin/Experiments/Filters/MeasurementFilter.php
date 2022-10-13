<?php

namespace App\Admin\Experiments\Filters;

use Domain\Experiment\Models\Measurement;

interface MeasurementFilter
{
    public function matches(Measurement $measurement): bool;
}
