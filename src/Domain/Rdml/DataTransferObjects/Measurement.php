<?php

declare(strict_types=1);

namespace Domain\Rdml\DataTransferObjects;

use App\Models\Measurement as MeasurementModel;
use Domain\Rdml\Enums\MeasurementType;
use Illuminate\Support\Collection;
use Spatie\DataTransferObject\DataTransferObject;

final class Measurement extends DataTransferObject
{
    public ?float $quantity = null;

    public string $sample;

    public string $target;

    public string $position;

    public bool $excluded = false;

    public ?float $cq = null;

    public MeasurementType $type;

    /** @var Collection<AmplificationDataPoint> */
    public Collection $amplificationDataPoints;

    public static function fromModel(MeasurementModel $measurement): Measurement
    {
        return new Measurement([
            'sample' => (string) $measurement->sample_id,
            'target' => $measurement->target,
            'position' => $measurement->position,
            'excluded' => $measurement->excluded,
            'cq' => $measurement->cq,
            'type' => $measurement->type,
            'amplificationDataPoints' => collect(),
        ]);
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
