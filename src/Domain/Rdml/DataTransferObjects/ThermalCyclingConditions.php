<?php

declare(strict_types=1);

namespace Domain\Rdml\DataTransferObjects;

use Domain\Rdml\Collections\StepCollection;
use Spatie\DataTransferObject\DataTransferObject;

final class ThermalCyclingConditions extends DataTransferObject
{
    public string $id;

    public ?string $description;

    public ?float $lidTemperature;

    public StepCollection $steps;
}
