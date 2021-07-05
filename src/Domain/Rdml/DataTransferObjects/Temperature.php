<?php

declare(strict_types=1);

namespace Domain\Rdml\DataTransferObjects;

use Spatie\DataTransferObject\DataTransferObject;

final class Temperature extends DataTransferObject
{
    public float $temperature;

    public int $duration;
}
