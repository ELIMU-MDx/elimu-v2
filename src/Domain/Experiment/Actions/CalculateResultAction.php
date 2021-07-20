<?php

declare(strict_types=1);

namespace Domain\Experiment\Actions;

use Domain\Evaluation\Collections\DataPointCollection;
use Domain\Evaluation\Enums\QualitativeResult;
use Domain\Experiment\DataTransferObjects\Result;
use Domain\Experiment\DataTransferObjects\ResultCalculationParameter;
use Illuminate\Support\Collection;

final class CalculateResultAction
{
    /**
     * @throws \Spatie\DataTransferObject\Exceptions\UnknownProperties
     */
    public function execute(
        string $target,
        DataPointCollection $dataPoints,
        ResultCalculationParameter $parameters
    ): Result {
        $qualification = $dataPoints->qualify($parameters->cutoff);

        return new Result([
            'target' => $target,
            'cq' => $dataPoints->averageCq()->raw(),
            'quantification' => $parameters->shouldQuantify() && $qualification === QualitativeResult::POSITIVE()
                ? $dataPoints->quantify($parameters->slope, $parameters->intercept)
                : null,
            'qualification' => $dataPoints->qualify($parameters->cutoff),
            'standardDeviation' => $dataPoints->count() > 1 ? $dataPoints->standardDeviationCq()->raw() : null,
            'replications' => $dataPoints->count(),
            'errors' => new Collection(),
        ]);
    }
}
