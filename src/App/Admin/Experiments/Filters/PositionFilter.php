<?php

namespace App\Admin\Experiments\Filters;

use Domain\Experiment\Models\Measurement;

final class PositionFilter implements MeasurementFilter
{
    /**
     * @param  string[]  $positions
     */
    public function __construct(private array $positions)
    {
    }

    public function matches(Measurement $measurement): bool
    {
        return in_array($measurement->position, $this->positions, true);
    }
}
