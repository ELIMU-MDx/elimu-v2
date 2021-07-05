<?php

declare(strict_types=1);

namespace Domain\Rdml\DataTransferObjects;

use Carbon\CarbonImmutable;
use Spatie\DataTransferObject\DataTransferObject;

final class Rdml extends DataTransferObject
{
    public string $version;

    public ?CarbonImmutable $createdAt;

    public ?CarbonImmutable $updatedAt;

    /** @var \Domain\Rdml\DataTransferObjects\Experimenter[] */
    public array $experimenter;

    /** @var \Domain\Rdml\DataTransferObjects\Dye[] */
    public array $dyes;

    /** @var \Domain\Rdml\DataTransferObjects\Sample[] */
    public array $samples;

    /** @var \Domain\Rdml\DataTransferObjects\Target[] */
    public array $targets;

    /** @var \Domain\Rdml\DataTransferObjects\ThermalCyclingConditions[] */
    public array $thermalCyclingConditions;

    /** @var \Domain\Rdml\DataTransferObjects\Experiment[] */
    public array $experiments;
}
