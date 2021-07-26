<?php

declare(strict_types=1);

namespace Domain\Rdml\Collections;

use Domain\Rdml\DataTransferObjects\Measurement;
use Domain\Rdml\Enums\MeasurementType;
use Domain\Results\Enums\QualitativeResult;
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

    public function quantify(float $slope, float $intercept): RoundedNumber
    {
        return new RoundedNumber(10 ** ($slope * $this->averageCq()->raw() + $intercept));
    }

    public function positives(float $cutoff): MeasurementCollection
    {
        return $this->filter(function (Measurement $measurement) use ($cutoff) {
            return Math::qualifyCq($measurement->cq, $cutoff) === QualitativeResult::POSITIVE();
        });
    }

    public function withoutControls(): MeasurementCollection
    {
        return $this->filter(function (Measurement $measurement) {
            return $measurement->type === MeasurementType::SAMPLE();
        });
    }

    public function controls(): MeasurementCollection
    {
        return $this->filter(function (Measurement $measurement) {
            return $measurement->type !== MeasurementType::SAMPLE();
        });
    }

    public function included(): MeasurementCollection
    {
        return $this->reject(function (Measurement $measurement) {
            return $measurement->excluded;
        });
    }
}
