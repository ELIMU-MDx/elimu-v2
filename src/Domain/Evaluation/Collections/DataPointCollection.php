<?php

declare(strict_types=1);

namespace Domain\Evaluation\Collections;

use Domain\Evaluation\DataTransferObjects\DataPoint;
use Domain\Evaluation\Enums\QualitativeResult;
use Support\CustomCollection;
use Support\Math;
use Support\RoundedNumber;

/**
 * @method DataPoint offsetGet($key)
 * @method DataPoint first(callable $callback = null, ?DataPoint $default = null)
 * @method DataPoint last(callable $callback = null, ?DataPoint $default = null)
 * @method DataPoint firstWhere($key, $operator = null, $value = null)
 */
final class DataPointCollection extends CustomCollection
{
    public function included(): static
    {
        return $this->filter(function (DataPoint $dataPoint) {
            return !$dataPoint->excluded;
        });
    }

    public function qualify(float $cutoff): QualitativeResult
    {
        return Math::qualifyCq($this->averageCq()->raw(), $cutoff);
    }

    public function standardDeviationCq(): RoundedNumber
    {
        return new RoundedNumber($this->standardDeviation(function (DataPoint $point) {
            return $point->cq;
        }));
    }

    public function averageCq(): RoundedNumber
    {
        return new RoundedNumber($this->avg(function (DataPoint $point) {
            return $point->cq;
        }));
    }

    public function quantify(float $slope, float $intercept): float
    {
        return round(10 ** ($slope * $this->averageCq()->raw() + $intercept), 2);
    }

    public function positives(float $cutoff): DataPointCollection
    {
        return $this->filter(function (DataPoint $point) use ($cutoff) {
            return Math::qualifyCq($point->cq, $cutoff) === QualitativeResult::POSITIVE();
        });
    }
}
