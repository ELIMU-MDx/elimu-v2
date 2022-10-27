<?php

namespace App\Admin\Data;

use Domain\Results\Enums\QualitativeResult;
use Support\Data;

final class SampleReportTarget extends Data
{
    public function __construct(
        readonly public string $name,
        readonly public ?float $cq,
        readonly public ?float $quantification,
        readonly public QualitativeResult $qualification,
    )
    {

    }
}
