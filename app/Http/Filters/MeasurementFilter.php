<?php

namespace App\Http\Filters;

use App\Models\Measurement;

interface MeasurementFilter
{
    public function matches(Measurement $measurement): bool;
}
