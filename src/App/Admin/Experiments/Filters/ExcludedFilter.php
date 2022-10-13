<?php

namespace App\Admin\Experiments\Filters;

use Domain\Experiment\Models\Measurement;

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
