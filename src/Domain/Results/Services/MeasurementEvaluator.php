<?php

declare(strict_types=1);

namespace Domain\Results\Services;

use BadMethodCallException;
use Domain\Experiment\DataTransferObjects\ResultCalculationParameter;
use Domain\Rdml\Collections\MeasurementCollection;
use Domain\Rdml\DataTransferObjects\Measurement;
use Domain\Results\DataTransferObjects\Result;
use Domain\Results\Enums\QualitativeResult;
use Illuminate\Support\Collection;

final class MeasurementEvaluator
{
    /**
     * @param  Collection  $measurements
     * @param  Collection<ResultCalculationParameter>  $parameters
     *
     * @return \Illuminate\Support\Collection<Result>
     */
    public function results(Collection $measurements, Collection $parameters): Collection
    {
        if (! ($measurements instanceof MeasurementCollection)) {
            $measurements = MeasurementCollection::make($measurements);
        }

        $parameters = $parameters->keyBy('target');

        return $measurements
            ->groupBy(function (Measurement $measurement) {
                return "{$measurement->sample}-{$measurement->target}";
            })
            ->map(function (MeasurementCollection $measurements) use ($parameters) {
                $target = $measurements->first()->target;
                $onlyIncludedMeasurements = $measurements->included();

                /** @var ResultCalculationParameter $parameter */
                $parameter = $parameters->first(function (ResultCalculationParameter $parameter) use ($target) {
                    return strcasecmp($parameter->target, $target) === 0;
                }) ?? throw new BadMethodCallException("No parameter for target {$target} provided");

                $qualification = $onlyIncludedMeasurements->qualify($parameter->cutoff);

                return new Result([
                    'sample' => $measurements->first()->sample,
                    'target' => $target,
                    'averageCQ' => $onlyIncludedMeasurements->averageCq(),
                    'repetitions' => $onlyIncludedMeasurements->count(),
                    'qualification' => $qualification,
                    'quantification' => $parameter->shouldQuantify() && $qualification === QualitativeResult::POSITIVE()
                        ? $onlyIncludedMeasurements->quantify($parameter->slope, $parameter->intercept)
                        : null,
                    'measurements' => $measurements,
                    'type' => $measurements->first()->type,
                ]);
            })
            ->toBase();
    }
}
