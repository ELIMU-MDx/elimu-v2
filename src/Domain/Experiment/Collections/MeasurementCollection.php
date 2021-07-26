<?php

declare(strict_types=1);

namespace Domain\Experiment\Collections;

use Illuminate\Database\Eloquent\Collection;

final class MeasurementCollection extends Collection
{
    public function included(): MeasurementCollection
    {
        return $this->reject->excluded;
    }
}
