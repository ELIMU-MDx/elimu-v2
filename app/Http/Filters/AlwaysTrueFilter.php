<?php

namespace App\Http\Filters;

use App\Models\Measurement;

final class AlwaysTrueFilter implements MeasurementFilter
{
    public function matches(Measurement $measurement): bool
    {
        return true;
    }
}
