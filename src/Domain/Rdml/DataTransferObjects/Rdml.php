<?php

declare(strict_types=1);

namespace Domain\Rdml\DataTransferObjects;

use Carbon\CarbonImmutable;
use Domain\Rdml\Collections\MeasurementCollection;
use Domain\Rdml\Collections\TargetCollection;
use Illuminate\Support\Collection;
use Support\Data;

final class Rdml extends Data
{
    /**
     * @param  Collection<QuantifyConfiguration>  $quantifyConfigurations
     */
    public function __construct(
        public string $version,
        public TargetCollection $targets,
        public MeasurementCollection $measurements,
        public Collection $quantifyConfigurations,
        public ?string $instrument = null,
        public ?CarbonImmutable $createdAt = null,
        public ?CarbonImmutable $updatedAt = null,
    ) {

    }
}
