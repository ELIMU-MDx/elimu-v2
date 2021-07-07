<?php

declare(strict_types=1);

namespace Domain\Rdml\DataTransferObjects;

use Illuminate\Contracts\Support\Arrayable;
use Spatie\DataTransferObject\DataTransferObject;

final class AmplificationDataPoint extends DataTransferObject implements Arrayable
{
    public float $cycle;

    public ?float $temperature;

    public float $fluor;
}
