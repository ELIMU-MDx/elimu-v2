<?php

declare(strict_types=1);

namespace Domain\Experiment\Actions;

use Domain\Assay\Models\Assay;
use Domain\Assay\Models\AssayParameter;
use Domain\Experiment\DataTransferObjects\ResultCalculationParameter;
use Domain\Experiment\Models\Measurement;
use Domain\Rdml\Collections\MeasurementCollection;
use Domain\Rdml\DataTransferObjects\Measurement as MeasurementDTO;
use Domain\Results\DataTransferObjects\Result;
use Domain\Results\DataTransferObjects\ResultValidationParameter;
use Domain\Results\Enums\QualitativeResult;
use Domain\Results\Models\Result as ResultModel;
use Domain\Results\Models\ResultError;
use Domain\Results\ResultValidationErrors\ResultValidationErrorFactory;
use Domain\Results\Services\MeasurementEvaluator;
use Domain\Results\Services\ResultValidator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection as BaseCollection;
use Support\Math;
use Support\ValueObjects\RoundedNumber;

final class RecalculateResultsAction
{
    public function __construct(
        private MeasurementEvaluator $measurementEvaluator,
        private ResultValidator $resultValidator,
    ) {
    }

    public function execute(Collection $measurements): void
    {
        if ($measurements->isEmpty()) {
            return;
        }

        $measurements
            ->load([
                'sample:id,identifier',
                'experiment.assay.parameters',
                'experiment.quantifyParameters',
            ])
            ->groupBy('experiment.assay_id')
            ->each(function (Collection $measurements) {
                $results = $this->calculateResults($measurements, $measurements->first()->experiment->assay->parameters);
                $resultModels = $this->storeResults($results, $measurements->first()->experiment->assay_id);
                $this->storeMeasurements($resultModels, $measurements);

                $validationParameter = $this->getValidationParameter($measurements,
                    $measurements->first()->experiment->assay);
                $this->storeResultErrors(
                    $results,
                    $validationParameter,
                    $resultModels
                );
            });
    }

    private function storeResults(BaseCollection $results, int $assayId): Collection
    {
        $resultsData = $results->map(function (Result $result) use ($assayId) {
            return [
                'sample_id' => $result->sample,
                'assay_id' => $assayId,
                'target' => $result->target,
                'cq' => $result->averageCQ->rounded(15),
                'quantification' => $result->quantification?->rounded(2),
                'qualification' => $result->qualification,
                'standard_deviation' => $result->measurements->included()->standardDeviationCq()->rounded(15),
            ];
        })
            ->tap(function (BaseCollection $results) {
                ResultModel::upsert(
                    $results->toArray(),
                    ['sample_id', 'assay_id', 'target'],
                    ['cq', 'quantification', 'qualification', 'standard_deviation']
                );
            });

        return ResultModel::whereIn('sample_id', $resultsData->pluck('sample_id'))
            ->whereIn('assay_id', $resultsData->pluck('assay_id'))
            ->whereIn('target', $resultsData->pluck('target'))
            ->get()
            ->keyBy(function (ResultModel $result) {
                return "{$result->sample_id}-{$result->target}";
            });
    }

    /**
     * @param  Collection<Measurement>  $measurements
     * @return BaseCollection
     */
    private function getResultCalculationParameter(Collection $measurements): BaseCollection
    {
        $quantifyParameters = $measurements->first()
            ->experiment
            ->quantifyParameters
            ->keyBy('target');

        return $measurements->first()
            ->experiment
            ->assay
            ->parameters
            ->map(fn (AssayParameter $parameter) => new ResultCalculationParameter([
                'target' => $parameter->target,
                'cutoff' => $parameter->cutoff,
                'intercept' => $quantifyParameters[$parameter->target]->intercept ?? $parameter->intercept,
                'slope' => $quantifyParameters[$parameter->target]->slope ?? $parameter->slope,
            ]))
            ->toBase();
    }

    private function storeResult(Result $result, Collection $measurements): ResultModel
    {
        return tap(
            ResultModel::firstOrNew([
                'sample_id' => $result->sample,
                'assay_id' => $measurements->first()->experiment->assay_id,
                'target' => $result->target,
            ])->fill([
                'cq' => $result->averageCQ->rounded(),
                'quantification' => $result->quantification?->rounded(),
                'qualification' => $result->qualification,
                'standard_deviation' => $result->measurements->standardDeviationCq(),
            ]),
            static function (ResultModel $result) use ($measurements) {
                $result->save();
                $result->measurements()->saveMany($measurements);
                $result->resultErrors()->delete();
            }
        );
    }

    private function storeResultErrors(
        BaseCollection $results,
        BaseCollection $validationParameter,
        Collection $resultModels
    ): void {
        ResultError::whereIn('result_id', $resultModels->pluck('id'))->delete();

        ResultError::insert(
            $results->flatMap(function (Result $result) use ($resultModels, $validationParameter) {
                return $this->resultValidator->validate($result, $validationParameter->get(strtolower($result->target)))
                    ->map(function (string $errorIdentifier) use ($resultModels, $validationParameter, $result) {
                        return [
                            'error' => ResultValidationErrorFactory::get($errorIdentifier)
                                ->message($result, $validationParameter->get(strtolower($result->target))),
                            'result_id' => $resultModels->get("{$result->sample}-{$result->target}")->id,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                    });
            })->toArray()
        );
    }

    private function storeMeasurements(Collection $resultModels, Collection $measurements): void
    {
        $measurements
            ->groupBy(function (Measurement $measurement) {
                return "{$measurement->sample_id}-{$measurement->target}";
            })
            ->each(function (Collection $measurements, string $sampleAndTarget) use ($resultModels) {
                Measurement::whereIn('id', $measurements->pluck('id'))
                    ->update(['result_id' => $resultModels->get($sampleAndTarget)->id]);
            });
    }

    /**
     * @param  Collection<Measurement>  $measurements
     * @param  Collection<AssayParameter>  $parameter
     * @return BaseCollection<Result>
     */
    private function calculateResults(Collection $measurements, Collection $parameters): BaseCollection
    {
        return $measurements
            ->groupBy('experiment_id')
            ->map(fn (Collection $groupedMeasurements) => $this->measurementEvaluator
                ->results(
                    $groupedMeasurements->map(function (Measurement $measurement) {
                        return MeasurementDTO::fromModel($measurement);
                    }),
                    $this->getResultCalculationParameter($groupedMeasurements)
                )
            )
            ->flatten(1)
            ->groupBy(fn (Result $result) => "$result->sample-$result->target")
            ->map(fn (BaseCollection $results) => new Result([
                'sample' => $results->first()->sample,
                'target' => $results->first()->target,
                'averageCQ' => new RoundedNumber($results->avg(fn (Result $result) => $result->averageCQ->raw())),
                'repetitions' => $results->sum(fn (Result $result) => $result->repetitions),
                'qualification' => Math::qualifyCq($results->avg(fn (Result $result) => $result->averageCQ->raw()),
                    $parameters->firstWhere('target', $results->first()->target)->cutoff),
                'quantification' => new RoundedNumber($results->avg(fn (Result $result) => $result->quantification?->raw())) ?: null,
                'measurements' => new MeasurementCollection($results->map(fn (Result $result) => $result->measurements)->flatten(1)->values()),
                'type' => $results->first()->type,
            ])
            )->each(function (Result $result) {
                if ($result->qualification !== QualitativeResult::POSITIVE()) {
                    $result->quantification = null;
                }
            });
    }

    private function getValidationParameter(Collection $measurements, Assay $assay): BaseCollection
    {
        return $measurements->first()
            ->experiment
            ->assay
            ->parameters
            ->mapWithKeys(function (AssayParameter $parameter) {
                return [
                    strtolower($parameter->target) => ResultValidationParameter::fromModel($parameter),
                ];
            });
    }
}
