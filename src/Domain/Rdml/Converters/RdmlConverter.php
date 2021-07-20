<?php

declare(strict_types=1);

namespace Domain\Rdml\Converters;

use Domain\Evaluation\Collections\DataPointCollection;
use Domain\Evaluation\Collections\SampleDataCollection;
use Domain\Evaluation\Collections\TargetDataCollection;
use Domain\Evaluation\DataTransferObjects\DataPoint;
use Domain\Evaluation\DataTransferObjects\SampleData;
use Domain\Evaluation\DataTransferObjects\TargetData;
use Domain\Experiment\Models\Measurement;
use Domain\Experiment\Models\Sample;
use Domain\Rdml\DataTransferObjects\Measurement as MeasurementDTO;
use Domain\Rdml\DataTransferObjects\Rdml;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Collection;

final class RdmlConverter implements Arrayable
{
    private Rdml $rdml;

    public function __construct(Rdml $rdml)
    {
        $this->rdml = $rdml;
    }

    /**
     * @return Collection
     */
    public function toMeasurements(): Collection
    {
        $sampleLookupTable = $this->rdml
            ->measurements
            ->pluck('sample')
            ->mapWithKeys(function (string $sampleIdentifier) {
                return [
                    $sampleIdentifier => new Sample([
                        'identifier' => $sampleIdentifier,
                    ]),
                ];
            });

        return $this->rdml
            ->measurements
            ->map(function (MeasurementDTO $measurement) use ($sampleLookupTable) {
                return new Measurement([
                    'sample' => $sampleLookupTable->get($measurement->sample),
                    'cq' => $measurement->cq,
                    'position' => $measurement->position,
                    'excluded' => $measurement->excluded,
                    'target' => $measurement->target,
                ]);
            });
    }

    public function toSampleData(): SampleDataCollection
    {
        return SampleDataCollection::make($this->rdml->measurements)
            ->groupBy(['sample', 'target'])
            ->map(function (Collection $targets, string $sampleId) {
                return new SampleData([
                    'id' => $sampleId,
                    'targets' => TargetDataCollection::make($targets)
                        ->map(function (Collection $measurements, string $target) {
                            return new TargetData([
                                'id' => $target,
                                'dataPoints' => DataPointCollection::make($measurements)
                                    ->map(function (MeasurementDTO $measurement) {
                                        return new DataPoint([
                                            'target' => $measurement->target,
                                            'cq' => $measurement->cq,
                                            'excluded' => $measurement->excluded,
                                        ]);
                                    }),
                            ]);
                        }),
                ]);
            });
    }

    public function toArray(): array
    {
        return json_decode($this->rdml->measurements->toJson(), true);
    }
}
