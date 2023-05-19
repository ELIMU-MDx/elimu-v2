<?php

declare(strict_types=1);

namespace Support;

use Illuminate\Support\Collection;

class CustomCollection extends Collection
{
    public function standardDeviation(callable | string | null $callback = null): float
    {
        $callback = $this->valueRetriever($callback);

        $items = $this->map(fn($value) => $callback($value))->filter(fn($value) => ! is_null($value));

        if ($items->count() <= 1) {
            return 0.0;
        }

        $average = (float) $items->avg();

        $variance = $items->reduce(fn(float $variance, float | int $value) => $variance + (($value - $average) ** 2), 0.0);

        return sqrt($variance / $items->count());
    }
}
