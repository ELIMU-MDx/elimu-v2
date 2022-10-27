<?php

namespace App\Admin\Data;

use Domain\Results\Enums\QualitativeResult;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

final class SampleReportData extends Data
{
    public function __construct(
        readonly public string $sampleId,
        readonly public string $assayName,
        readonly public QualitativeResult $result,
        #[DataCollectionOf(SampleReportTarget::class)]
        readonly public DataCollection $targets,
        #[DataCollectionOf(SampleReportTarget::class)]
        readonly public DataCollection $controlTargets,
    )
    {

    }
}
