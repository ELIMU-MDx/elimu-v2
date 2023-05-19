<?php

namespace App\Http\Filters;

use App\Models\Measurement;

final class ExcludedFilter implements MeasurementFilter
{
    public function __construct(private bool $showExcluded)
    {
    }

    public function matches(Measurement $measurement): bool
    {
        if ($this->showExcluded) {
            return true;
        }

        return ! $measurement->excluded;
    }
}
