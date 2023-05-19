<?php

declare(strict_types=1);

namespace Domain\Rdml\Collections;

use Domain\Rdml\DataTransferObjects\Measurement;
use Domain\Rdml\Enums\MeasurementType;
use Domain\Results\Enums\QualitativeResult;
use Support\CustomCollection;
use Support\Math;
use Support\ValueObjects\RoundedNumber;

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
        return new RoundedNumber($this->avg(fn (Measurement $measurement) => $measurement->cq));
    }

    public function standardDeviationCq(): RoundedNumber
    {
        return new RoundedNumber($this->standardDeviation(fn (Measurement $measurement) => $measurement->cq));
    }

    public function coefficientOfVariation(): RoundedNumber
    {
        return new RoundedNumber($this->standardDeviationCq()->raw() / $this->averageCq()->raw());
    }

    public function quantify(float $slope, float $intercept): ?RoundedNumber
    {
        if ($this->count() === 0) {
            return null;
        }

        return new RoundedNumber(10 ** (($this->averageCq()->raw() - $intercept) / $slope));
    }

    public function positives(float $cutoff): MeasurementCollection
    {
        return $this->filter(fn (Measurement $measurement) => Math::qualifyCq($measurement->cq, $cutoff) === QualitativeResult::POSITIVE);
    }

    public function withoutControls(): MeasurementCollection
    {
        return $this->filter(fn (Measurement $measurement) => $measurement->type === MeasurementType::SAMPLE);
    }

    public function controls(): MeasurementCollection
    {
        return $this->filter(fn (Measurement $measurement) => in_array($measurement->type, [
            MeasurementType::NEGATIVE_CONTROL, MeasurementType::POSTIVE_CONTROL, MeasurementType::NTC_CONTROL,
        ], true));
    }

    public function included(): MeasurementCollection
    {
        return $this->reject(fn (Measurement $measurement) => $measurement->excluded);
    }

    public function standards(): StandardsCollection
    {
        return new StandardsCollection(
            $this->filter(fn (Measurement $measurement) => $measurement->type === MeasurementType::STANDARD)
        );
    }
}
