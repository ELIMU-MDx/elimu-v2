<?php

declare(strict_types=1);

namespace Domain\Experiment\DataTransferObjects;

use Illuminate\Support\Collection;
use Spatie\DataTransferObject\DataTransferObject;

final class Result extends DataTransferObject
{
    public string $target;

    public ?float $cq;

    public ?float $quantification;

    public string $qualification;

    public ?float $standardDeviation;

    public int $replications;

    /** @var \Illuminate\Support\Collection<\Domain\Results\ResultValidationErrors\ResultValidationError> */
    public Collection $errors;
}
