<?php

declare(strict_types=1);

namespace Domain\Experiment\Actions;

use Domain\Evaluation\Collections\DataPointCollection;
use Domain\Evaluation\DataTransferObjects\SampleValidationParameter;
use Domain\Evaluation\ResultValidationErrors\ResultValidationError;
use Domain\Evaluation\ResultValidationErrors\ResultValidationErrorFactory;
use Illuminate\Support\Collection;

final class DataPointValidationAction
{
    /**
     * @param  \Domain\Evaluation\Collections\DataPointCollection  $dataPoints
     * @param  \Domain\Evaluation\DataTransferObjects\SampleValidationParameter  $parameter
     * @return \Illuminate\Support\Collection<ResultValidationError>
     */
    public function execute(DataPointCollection $dataPoints, SampleValidationParameter $parameter): Collection
    {
        return ResultValidationErrorFactory::all()
            ->filter(function (ResultValidationError $error) use ($parameter, $dataPoints) {
                return !$error->validate($dataPoints, $parameter);
            });
    }
}
