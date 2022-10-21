<?php

namespace Domain\Experiment\DataTransferObjects;

use Carbon\CarbonImmutable;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\DataCollection;
use Support\Data;

final class ExperimentListItem extends Data
{
    public function __construct(
        readonly public int $experimentId,
        readonly public int $studyId,
        readonly public string $name,
        readonly public bool $importPending,
        readonly public int $numberOfSamples,
        readonly public string $assay,
        readonly public CarbonImmutable $runDate,
        readonly public CarbonImmutable $uploadedDate,
        #[DataCollectionOf(ExperimentTarget::class)]
        readonly public DataCollection $targets,
        readonly public ?string $eln = null,
        readonly public ?string $instrument = null,
    ) {
    }
}
