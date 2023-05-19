<?php

namespace App\Http\Filters;

use App\Models\Measurement;

final class TargetFilter implements MeasurementFilter
{
    /**
     * @param  string[]  $targets
     */
    public function __construct(private readonly array $targets)
    {
    }

    public function matches(Measurement $measurement): bool
    {
        return in_array($measurement->target, $this->targets, true);
    }
}
