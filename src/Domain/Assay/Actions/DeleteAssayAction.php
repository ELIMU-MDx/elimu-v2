<?php

declare(strict_types=1);

namespace Domain\Assay\Actions;

use Domain\Assay\Models\Assay;
use Domain\Experiment\Models\Sample;
use Domain\Results\Models\Result;

final class DeleteAssayAction
{
    public function execute(Assay $assay): void
    {
        $assay->delete();
        Result::whereDoesntHave('measurements')->delete();
        Sample::whereDoesntHave('results')->delete();
    }
}
