<?php

namespace Domain\Rdml\Collections;

use Correlation\Correlation;
use Domain\Rdml\DataTransferObjects\Measurement;
use Domain\Rdml\DataTransferObjects\QuantifyConfiguration;
use Illuminate\Support\Collection;

final class StandardsCollection extends Collection
{
    public function nonNullDataPoints(): Collection
    {
        return $this
            ->filter(fn(Measurement $measurement) => $measurement->cq)
            ->map(fn(Measurement $measurement) => [
                'x' => log10($measurement->quantity), 'y' => $measurement->cq,
            ])
            ->values()
            ->toBase();
    }

    public function linearRegression(): array
    {
        return linear_regression($this->nonNullDataPoints()->toArray());
    }

    public function correlationCoefficient(): float|int
    {
        $dataPoints = $this->nonNullDataPoints();

        return (new Correlation())
            ->pearson($dataPoints->pluck('x')->toArray(), $dataPoints->pluck('y')->toArray())
            ->coefficient;
    }

    public function quantifyConfiguration(): QuantifyConfiguration
    {
        $regression = $this->linearRegression();

        return new QuantifyConfiguration(
            [
                'target' => $this->first()->target,
                'slope' => round($regression['slope'], 4),
                'intercept' => round($regression['intercept'], 4),
                'correlationCoefficient' => round($this->correlationCoefficient(), 4),
            ],
        );
    }
}
