<?php

declare(strict_types=1);

namespace Domain\Experiment\Actions;

use Domain\Assay\Models\Assay;
use Domain\Evaluation\Collections\DataPointCollection;
use Domain\Evaluation\Collections\SampleDataCollection;
use Domain\Evaluation\Collections\TargetDataCollection;
use Domain\Evaluation\DataTransferObjects\DataPoint;
use Domain\Evaluation\DataTransferObjects\SampleData;
use Domain\Evaluation\DataTransferObjects\TargetData;
use Domain\Experiment\Models\Measurement;
use Domain\Experiment\Models\Sample;
use Illuminate\Database\Eloquent\Collection;

final class StoreResultAction
{
    public function execute(Collection $samples, Assay $assay)
    {
        $sampleDataCollection = $this->convertToSampleData($samples);
    }

    private function convertToSampleData(Collection $samples): SampleDataCollection
    {
        return SampleDataCollection::make($samples->load('measurements'))
            ->map(function (Sample $sample) {
                return new SampleData([
                    'id' => $sample->identifier,
                    TargetDataCollection::make($sample->measurements)->groupBy('target')
                        ->map(function (Collection $measurements, string $target) {
                            return new TargetData([
                                'id' => $target,
                                'dataPoints' => DataPointCollection::make($measurements)
                                    ->map(function (Measurement $measurement) {
                                        return new DataPoint([
                                            'cq' => $measurement->cq,
                                            'position' => $measurement->position,
                                            'excluded' => $measurement->excluded,
                                        ]);
                                    }),
                            ]);
                        }),
                ]);
            });
    }
}
