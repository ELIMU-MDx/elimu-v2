<?php

declare(strict_types=1);

namespace Domain\Rdml\Converters;

use Domain\Evaluation\Collections\DataPointCollection;
use Domain\Evaluation\Collections\SampleDataCollection;
use Domain\Evaluation\Collections\TargetDataCollection;
use Domain\Evaluation\DataTransferObjects\DataPoint;
use Domain\Evaluation\DataTransferObjects\SampleData;
use Domain\Evaluation\DataTransferObjects\TargetData;
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

    public function toSampleData(): SampleDataCollection
    {
        $result = new Collection($this->toArray());

        return new SampleDataCollection($result->pluck('runs.*.reactions')
            ->flatten(2)
            ->groupBy('sample.id')
            ->map(function (Collection $reactions) {
                return $reactions->groupBy('data.*.target.id')
                    ->map(function (Collection $reactions) {
                        return $reactions->pluck('data.*.cq')
                            ->flatten()
                            ->map(function (?float $cq) {
                                return new DataPoint(cq: $cq);
                            });
                    });
            })
            ->map(function (Collection $targets, string $sampleId) {
                return new SampleData([
                    'id' => $sampleId,
                    'targets' => new TargetDataCollection(
                        $targets->map(function (Collection $dataPoints, string $targetId) {
                            return new TargetData([
                                'id' => $targetId,
                                'dataPoints' => new DataPointCollection($dataPoints),
                            ]);
                        })),
                ]);
            }));
    }

    public function toArray(): array
    {
        return json_decode($this->rdml->experiments->toJson(), true);
    }
}
