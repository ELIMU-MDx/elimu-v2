<?php

namespace App\Admin\Experiments\Filters;

use Domain\Experiment\Models\Measurement;

final class TargetFilter implements MeasurementFilter
{
    /**
     * @param  string[]  $targets
     */
    public function __construct(private array $targets)
    {

    }

    public function matches(Measurement $measurement): bool
    {
        if(empty($this->targets)) {
            return true;
        }

        return in_array($measurement->target, $this->targets, true);
    }
}
