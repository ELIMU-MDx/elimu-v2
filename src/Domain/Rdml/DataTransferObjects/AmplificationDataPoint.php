<?php

declare(strict_types=1);

namespace Domain\Rdml\DataTransferObjects;

use Spatie\DataTransferObject\DataTransferObject;

final class AmplificationDataPoint extends DataTransferObject
{
    public float $cylce;

    public ?float $temperature;

    public float $fluor;
}
