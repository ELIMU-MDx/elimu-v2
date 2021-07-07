<?php

declare(strict_types=1);

namespace Domain\Rdml\DataTransferObjects;

use Illuminate\Contracts\Support\Arrayable;
use Spatie\DataTransferObject\DataTransferObject;

final class Temperature extends DataTransferObject implements Arrayable
{
    public float $temperature;

    public int $duration;
}
