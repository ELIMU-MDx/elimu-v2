<?php

namespace App\Http\Filters;

final class MeasurementFilterFactory
{
    /** @var class-string<MeasurementFilter>[] */
    private array $filters = [
        'excluded' => ExcludedFilter::class,
        'experiment' => ExperimentFilter::class,
        'position' => PositionFilter::class,
        'target' => TargetFilter::class,
    ];

    public function get(string $name, array $arguments = []): MeasurementFilter
    {
        if (! array_key_exists($name, $this->filters)) {
            return new AlwaysTrueFilter();
        }

        return new $this->filters[$name](...array_values($arguments));
    }
}
