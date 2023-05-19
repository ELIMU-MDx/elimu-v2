<?php

namespace App\Http\Filters;

use App\Models\Measurement;

final class PositionFilter implements MeasurementFilter
{
    /**
     * @param  string[]  $positions
     */
    public function __construct(private readonly array $positions)
    {
    }

    public function matches(Measurement $measurement): bool
    {
        return in_array($measurement->position, $this->positions, true);
    }
}
