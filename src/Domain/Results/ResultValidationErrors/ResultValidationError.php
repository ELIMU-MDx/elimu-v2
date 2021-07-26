<?php

declare(strict_types=1);

namespace Domain\Results\ResultValidationErrors;

use Domain\Results\DataTransferObjects\Result;
use Domain\Results\DataTransferObjects\ResultValidationParameter;

interface ResultValidationError
{
    public function message(Result $result, ResultValidationParameter $parameter): string;

    public function validate(Result $result, ResultValidationParameter $parameter): bool;

    public function appliesFor(Result $result): bool;
}
