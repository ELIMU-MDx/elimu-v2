<?php

declare(strict_types=1);

namespace Domain\Experiment\Actions;

use Domain\Rdml\Collections\MeasurementCollection;
use Domain\Results\DataTransferObjects\ResultValidationParameter;
use Domain\Results\ResultValidationErrors\ResultValidationError;
use Domain\Results\ResultValidationErrors\ResultValidationErrorFactory;
use Illuminate\Support\Collection;

final class DataPointValidationAction
{
    /**
     * @param  MeasurementCollection  $dataPoints
     * @param  \Domain\Results\DataTransferObjects\ResultValidationParameter  $parameter
     *
     * @return \Illuminate\Support\Collection<ResultValidationError>
     */
    public function execute(MeasurementCollection $dataPoints, ResultValidationParameter $parameter): Collection
    {
        return ResultValidationErrorFactory::all()
            ->filter(function (ResultValidationError $error) use ($parameter, $dataPoints) {
                return ! $error->validate($dataPoints, $parameter);
            });
    }
}
