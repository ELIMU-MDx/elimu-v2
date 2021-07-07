<?php

declare(strict_types=1);

namespace Domain\Rdml\DataTransferObjects;

use Domain\Rdml\Collections\StepCollection;
use Illuminate\Contracts\Support\Arrayable;
use Spatie\DataTransferObject\DataTransferObject;

final class ThermalCyclingConditions extends DataTransferObject implements Arrayable
{
    public string $id;

    public ?string $description;

    public ?float $lidTemperature;

    public StepCollection $steps;
}
