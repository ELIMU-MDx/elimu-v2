<?php

declare(strict_types=1);

namespace Domain\Evaluation\DataTransferObjects;

use Domain\Evaluation\Enums\QualitativeResult;
use Illuminate\Contracts\Support\Arrayable;
use Spatie\DataTransferObject\DataTransferObject;

final class TargetResult extends DataTransferObject implements Arrayable
{
    public string $target;

    public ?float $cqAverage;

    public QualitativeResult $qualitative;

    public ?float $quantitative;
}
