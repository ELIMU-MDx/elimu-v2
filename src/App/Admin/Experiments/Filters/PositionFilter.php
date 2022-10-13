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
        if(empty($this->positions)) {
            return true;
        }

        return in_array($measurement->position, $this->positions, true);
    }
}
