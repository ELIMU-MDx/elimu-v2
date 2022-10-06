<?php

namespace Domain\Rdml\DataTransferObjects;

use Spatie\DataTransferObject\DataTransferObject;

final class QuantifyConfiguration extends DataTransferObject
{
    public string $target;

    public float $slope;

    public float $intercept;

    public float $correlationCoefficient;
}
