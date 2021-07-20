<?php

declare(strict_types=1);

namespace Domain\Experiment\Actions;

use Domain\Evaluation\Collections\DataPointCollection;
use Domain\Evaluation\DataTransferObjects\DataPoint;
use Domain\Evaluation\DataTransferObjects\SampleValidationParameter;
use Domain\Evaluation\Models\Result;
use Domain\Evaluation\Models\ResultError;
use Domain\Evaluation\ResultValidationErrors\ResultValidationError;
use Domain\Experiment\DataTransferObjects\ResultCalculationParameter;
use Domain\Experiment\Models\Measurement;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection as BaseCollection;

final class RecalculateResultsAction
{
    public function __construct(
        private CalculateResultAction $calculateResultAction,
        private DataPointValidationAction $dataPointValidationAction
    ) {
    }

    public function execute(Collection $measurements): void
    {
        $measurements
            ->load([
                'sample:id,identifier', 'experiment:id,assay_id', 'experiment.assay:id', 'experiment.assay.parameters',
            ])
            ->groupBy(function (Measurement $measurement) {
                return "{$measurement->experiment->assay_id}-{$measurement->sample_id}-{$measurement->target}";
            })
            ->each(function (Collection $measurements) {
                /** @var \Domain\Assay\Models\AssayParameter $targetParameter */
                $targetParameter = $measurements->first()->experiment->assay->parameters->getByTarget($measurements->first()->target);
                $dataPoints = $this->toDataPoints($measurements);

                $resultDTO = $this->calculateResultAction->execute(
                    $measurements->first()->target,
                    $dataPoints,
                    new ResultCalculationParameter([
                        'cutoff' => $targetParameter->cutoff,
                        'intercept' => $targetParameter->intercept,
                        'slope' => $targetParameter->slope,
                    ]));

                $result = Result::firstOrNew([
                    'sample_id' => $measurements->first()->sample_id,
                    'assay_id' => $measurements->first()->experiment->assay_id,
                    'target' => $resultDTO->target,
                ])->fill([
                    'cq' => $resultDTO->cq,
                    'quantification' => $resultDTO->quantification,
                    'qualification' => $resultDTO->qualification,
                    'standard_deviation' => $resultDTO->standardDeviation,
                ]);

                $result->save();

                $result->measurements()->saveMany($measurements);
                $result->resultErrors()->delete();

                $sampleValidationParameter = new SampleValidationParameter([
                    'cutoff' => $targetParameter->cutoff,
                    'standardDeviationCutoff' => $targetParameter->standard_deviation_cutoff,
                    'requiredRepetitions' => $targetParameter->required_repetitions,
                ]);
                $this->dataPointValidationAction
                    ->execute($dataPoints, $sampleValidationParameter)
                    ->map(function (ResultValidationError $error) use ($sampleValidationParameter, $dataPoints) {
                        return new ResultError(['error' => $error->message($dataPoints, $sampleValidationParameter)]);
                    })->tap(function (BaseCollection $resultErrors) use ($result) {
                        $result->resultErrors()->saveMany($resultErrors);
                    });
            });
    }

    private function toDataPoints(Collection $measurements): DataPointCollection
    {
        return DataPointCollection::make($measurements)
            ->map(function (Measurement $measurement) {
                return new DataPoint([
                    'target' => $measurement->target,
                    'cq' => $measurement->cq,
                    'excluded' => $measurement->excluded,
                ]);
            })->included();
    }
}
