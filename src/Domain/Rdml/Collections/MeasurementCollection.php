<?php

declare(strict_types=1);

namespace Domain\Rdml\Collections;

use Domain\Evaluation\Enums\QualitativeResult;
use Domain\Rdml\DataTransferObjects\Measurement;
use Support\CustomCollection;
use Support\Math;
use Support\RoundedNumber;

/**
 * @method Measurement offsetGet($key)
 * @method Measurement first(callable $callback = null, ?Measurement $default = null)
 * @method Measurement last(callable $callback = null, ?Measurement $default = null)
 * @method Measurement firstWhere($key, $operator = null, $value = null)
 */
final class MeasurementCollection extends CustomCollection
{
    public function qualify(float $cutoff): QualitativeResult
    {
        return Math::qualifyCq($this->averageCq()->raw(), $cutoff);
    }

    public function averageCq(): RoundedNumber
    {
        return new RoundedNumber($this->avg(function (Measurement $measurement) {
            return $measurement->cq;
        }));
    }

    public function standardDeviationCq(): RoundedNumber
    {
        return new RoundedNumber($this->standardDeviation(function (Measurement $measurement) {
            return $measurement->cq;
        }));
    }

    public function quantify(float $slope, float $intercept): float
    {
        return round(10 ** ($slope * $this->averageCq()->raw() + $intercept), 2);
    }

    public function positives(float $cutoff): MeasurementCollection
    {
        return $this->filter(function (Measurement $measurement) use ($cutoff) {
            return Math::qualifyCq($measurement->cq, $cutoff) === QualitativeResult::POSITIVE();
        });
    }
}
