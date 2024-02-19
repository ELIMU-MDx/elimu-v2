<?php

declare(strict_types=1);

namespace Domain\Rdml\DataTransferObjects;

use Support\Data;

final class AmplificationDataPoint extends Data
{
    public function __construct(
        public float $cycle,
        public ?float $temperature,
        public float $fluor,
    ) {

    }
}
