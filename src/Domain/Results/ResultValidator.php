<?php

declare(strict_types=1);

namespace Domain\Results;

use Domain\Results\DataTransferObjects\Result;
use Domain\Results\DataTransferObjects\ResultValidationParameter;
use Domain\Results\ResultValidationErrors\ResultValidationError;
use Domain\Results\ResultValidationErrors\ResultValidationErrorFactory;
use Illuminate\Support\Collection;

final class ResultValidator
{
    /**
     * @return Collection<ResultValidationError>
     */
    public function validate(Result $result, ResultValidationParameter $validationParameter): Collection
    {
        return ResultValidationErrorFactory::all()
            ->filter(fn(ResultValidationError $validator) => $validator->appliesFor($result) && ! $validator->validate($result, $validationParameter))->map(fn(ResultValidationError $validator) => $validator::IDENTIFIER);
    }
}
