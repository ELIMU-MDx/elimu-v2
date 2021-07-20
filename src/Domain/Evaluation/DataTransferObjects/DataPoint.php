<?php

declare(strict_types=1);

namespace Domain\Evaluation\DataTransferObjects;

use Illuminate\Contracts\Support\Arrayable;
use Spatie\DataTransferObject\DataTransferObject;

final class DataPoint extends DataTransferObject implements Arrayable
{
    public string $target;

    public ?float $cq;

    public bool $excluded = false;
}
