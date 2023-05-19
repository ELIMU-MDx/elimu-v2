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
     * @return Collection<ResultValidationError>
     */
    public function execute(MeasurementCollection $dataPoints, ResultValidationParameter $parameter): Collection
    {
        return ResultValidationErrorFactory::all()
            ->filter(fn (ResultValidationError $error) => ! $error->validate($dataPoints, $parameter));
    }
}
