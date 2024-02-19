<?php

namespace Domain\Experiment\DataTransferObjects;

use Carbon\CarbonImmutable;
use Illuminate\Support\Collection;
use Support\Data;

final class ExperimentListItem extends Data
{
    /**
     * @param  Collection<int, ExperimentTarget>  $targets
     */
    public function __construct(
        readonly public int $experimentId,
        readonly public int $studyId,
        readonly public string $name,
        readonly public bool $importPending,
        readonly public int $numberOfSamples,
        readonly public string $assay,
        readonly public CarbonImmutable $runDate,
        readonly public CarbonImmutable $uploadedDate,
        readonly public Collection $targets,
        readonly public ?string $eln = null,
        readonly public ?string $instrument = null,
    ) {
    }
}
