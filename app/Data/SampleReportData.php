<?php

namespace App\Data;

use Domain\Results\Enums\QualitativeResult;
use Illuminate\Support\Collection;
use Support\Data;

final class SampleReportData extends Data
{
    /**
     * @param  Collection<int, SampleReportTarget>  $targets
     * @param  Collection<int, SampleReportTarget>  $controlTargets
     */
    public function __construct(
        readonly public string $study,
        readonly public string $sampleId,
        readonly public string $assayName,
        readonly public bool $hasQuantification,
        readonly public QualitativeResult $result,
        readonly public Collection $targets,
        readonly public Collection $controlTargets,
    ) {
    }
}
