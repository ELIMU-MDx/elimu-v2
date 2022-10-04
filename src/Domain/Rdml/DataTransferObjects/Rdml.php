<?php

declare(strict_types=1);

namespace Domain\Rdml\DataTransferObjects;

use Carbon\CarbonImmutable;
use Domain\Rdml\Collections\MeasurementCollection;
use Domain\Rdml\Collections\TargetCollection;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Collection;
use Spatie\DataTransferObject\DataTransferObject;

final class Rdml extends DataTransferObject implements Arrayable
{
    public string $version;

    public ?CarbonImmutable $createdAt;

    public ?CarbonImmutable $updatedAt;

    public TargetCollection $targets;

    public MeasurementCollection $measurements;

    /** @var Collection<QuantifyConfiguration> */
    public Collection $quantifyConfigurations;
}
