<?php

namespace App\Data;

use Support\Data;

final class SampleReportTarget extends Data
{
    public function __construct(
        readonly public string $name,
        readonly public ?float $cq,
        readonly public ?float $quantification,
        readonly public string $qualification,
    ) {
    }
}
