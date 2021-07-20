<?php

declare(strict_types=1);

namespace Domain\Experiment\Collections;

use Domain\Experiment\Models\Measurement;
use Illuminate\Database\Eloquent\Collection;

final class MeasurementCollection extends Collection
{
    public function included(): static
    {
        return $this->filter(function (Measurement $measurement) {
            return !$measurement->excluded;
        });
    }
}
