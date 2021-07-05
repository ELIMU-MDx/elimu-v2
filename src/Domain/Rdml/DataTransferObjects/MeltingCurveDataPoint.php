<?php

declare(strict_types=1);

namespace Domain\Rdml\DataTransferObjects;

use Spatie\DataTransferObject\DataTransferObject;

final class MeltingCurveDataPoint extends DataTransferObject
{
    public float $temperature;

    public float $fluor;
}
