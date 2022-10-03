<?php

declare(strict_types=1);

namespace Domain\Results\Services;

use Domain\Results\DataTransferObjects\Result;
use Domain\Results\DataTransferObjects\ResultValidationParameter;
use Domain\Results\ResultValidationErrors\ResultValidationError;
use Domain\Results\ResultValidationErrors\ResultValidationErrorFactory;
use Illuminate\Support\Collection;

final class ResultValidator
{
    /**
     * @param  \Domain\Results\DataTransferObjects\Result  $result
     * @param  \Domain\Results\DataTransferObjects\ResultValidationParameter  $validationParameter
     * @return \Illuminate\Support\Collection<\Domain\Results\ResultValidationErrors\ResultValidationError>
     */
    public function validate(Result $result, ResultValidationParameter $validationParameter): Collection
    {
        return ResultValidationErrorFactory::all()
            ->filter(function (ResultValidationError $validator) use ($validationParameter, $result) {
                return $validator->appliesFor($result) && ! $validator->validate($result, $validationParameter);
            })->map(function (ResultValidationError $validator) {
                return $validator::IDENTIFIER;
            });
    }
}
