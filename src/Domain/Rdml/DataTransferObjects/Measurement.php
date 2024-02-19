<?php

declare(strict_types=1);

namespace Domain\Rdml\DataTransferObjects;

use App\Models\Measurement as MeasurementModel;
use Domain\Rdml\Enums\MeasurementType;
use Illuminate\Support\Collection;
use Support\Data;

final class Measurement extends Data
{
    /**
     * @param  Collection<AmplificationDataPoint>  $amplificationDataPoints
     */
    public function __construct(
        public string $sample,
        public string $target,
        public string $position,
        public bool $excluded,
        public MeasurementType $type,
        public Collection $amplificationDataPoints,
        public ?float $quantity = null,
        public ?float $cq = null,
    ) {

    }

    public static function fromModel(MeasurementModel $measurement): Measurement
    {
        return new Measurement(
            sample: (string) $measurement->sample_id,
            target: $measurement->target,
            position: $measurement->position,
            excluded: $measurement->excluded,
            type: $measurement->type,
            amplificationDataPoints: collect(),
            cq: $measurement->cq,
        );
    }

    public function is(MeasurementModel $measurement): bool
    {
        return $measurement->sample->identifier === $this->sample
            && $measurement->target === $this->target
            && $measurement->position === $this->position
            && $measurement->excluded === $this->excluded
            && $measurement->type === $this->type;
    }
}
