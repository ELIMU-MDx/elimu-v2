<?php

declare(strict_types=1);

namespace Domain\Experiment\DataTransferObjects;

use Domain\Results\ResultValidationErrors\ResultValidationError;
use Illuminate\Support\Collection;
use Spatie\DataTransferObject\DataTransferObject;

final class Result extends DataTransferObject
{
    public string $target;

    public ?float $cq = null;

    public ?float $quantification = null;

    public string $qualification;

    public ?float $standardDeviation = null;

    public int $replications;

    /** @var Collection<ResultValidationError> */
    public Collection $errors;
}
