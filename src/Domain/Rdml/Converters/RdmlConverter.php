<?php

declare(strict_types=1);

namespace Domain\Rdml\Converters;

use App\Models\Measurement;
use App\Models\Sample;
use Domain\Rdml\DataTransferObjects\Measurement as MeasurementDTO;
use Domain\Rdml\DataTransferObjects\Rdml;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Collection;

final class RdmlConverter implements Arrayable
{
    public function __construct(private readonly Rdml $rdml)
    {
    }

    public function toMeasurements(): Collection
    {
        $sampleLookupTable = $this->rdml
            ->measurements
            ->pluck('sample')
            ->mapWithKeys(fn (string $sampleIdentifier) => [
                $sampleIdentifier => new Sample([
                    'identifier' => $sampleIdentifier,
                ]),
            ]);

        return $this->rdml
            ->measurements
            ->map(fn (MeasurementDTO $measurement) => new Measurement([
                'sample' => $sampleLookupTable->get($measurement->sample),
                'cq' => $measurement->cq,
                'position' => $measurement->position,
                'excluded' => $measurement->excluded,
                'target' => $measurement->target,
                'type' => $measurement->type,
            ]));
    }

    public function toArray(): array
    {
        return json_decode($this->rdml->measurements->toJson(), true, 512, JSON_THROW_ON_ERROR);
    }
}
