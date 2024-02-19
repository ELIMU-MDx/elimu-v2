<?php

namespace Domain\Rdml\DataTransferObjects;

use Support\Data;

final class QuantifyConfiguration extends Data
{
    public function __construct(
        public string $target,
        public float $slope,
        public float $intercept,
        public float $correlationCoefficient,
    ) {
    }
}
