<?php

declare(strict_types=1);

namespace Domain\Rdml\DataTransferObjects;

use Spatie\DataTransferObject\DataTransferObject;

final class Measurement extends DataTransferObject
{
    public ?string $experiment;

    public ?string $run;

    public string $sample;

    public string $target;

    public string $position;

    public bool $excluded = false;

    public ?float $cq;
}
