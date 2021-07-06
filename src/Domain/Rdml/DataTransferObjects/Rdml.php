<?php

declare(strict_types=1);

namespace Domain\Rdml\DataTransferObjects;

use Carbon\CarbonImmutable;
use Domain\Rdml\Collections\DyeCollection;
use Domain\Rdml\Collections\ExperimentCollection;
use Domain\Rdml\Collections\ExperimenterCollection;
use Domain\Rdml\Collections\SampleCollection;
use Domain\Rdml\Collections\TargetCollection;
use Domain\Rdml\Collections\ThermalCyclingConditionsCollection;
use Spatie\DataTransferObject\DataTransferObject;

final class Rdml extends DataTransferObject
{
    public string $version;

    public ?CarbonImmutable $createdAt;

    public ?CarbonImmutable $updatedAt;

    public ExperimenterCollection $experimenter;

    public DyeCollection $dyes;

    public SampleCollection $samples;

    public TargetCollection $targets;

    public ThermalCyclingConditionsCollection $thermalCyclingConditions;

    public ExperimentCollection $experiments;
}
