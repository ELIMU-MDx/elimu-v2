<?php

declare(strict_types=1);

namespace Domain\Rdml\DataTransferObjects;

use Domain\Experiment\Models\Measurement as MeasurementModel;
use Domain\Rdml\Enums\MeasurementType;
use Spatie\DataTransferObject\DataTransferObject;

final class Measurement extends DataTransferObject
{
    public string $sample;

    public string $target;

    public string $position;

    public bool $excluded = false;

    public ?float $cq;

    public MeasurementType $type;

    public static function fromModel(MeasurementModel $measurement): Measurement
    {
        return new Measurement([
            'sample' => (string) $measurement->sample_id,
            'target' => $measurement->target,
            'position' => $measurement->position,
            'excluded' => $measurement->excluded,
            'cq' => $measurement->cq,
            'type' => $measurement->type,
        ]);
    }
}
