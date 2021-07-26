<?php

declare(strict_types=1);

namespace Support;

use Illuminate\Support\Collection;

class CustomCollection extends Collection
{
    public function standardDeviation(callable|string|null $callback = null): float
    {
        $callback = $this->valueRetriever($callback);

        $items = $this->map(function ($value) use ($callback) {
            return $callback($value);
        })->filter(function ($value) {
            return !is_null($value);
        });

        if ($items->isEmpty()) {
            return 0.0;
        }

        $average = (float) $items->avg();

        $variance = $items->reduce(function (float $variance, float|int $value) use ($average) {
            return ($value - $average) ** 2;
        }, 0.0);

        return sqrt($variance / $items->count());
    }
}
