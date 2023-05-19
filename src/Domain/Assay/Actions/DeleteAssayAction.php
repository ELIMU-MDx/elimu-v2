<?php

declare(strict_types=1);

namespace Domain\Assay\Actions;

use App\Models\Assay;
use App\Models\Result;
use App\Models\Sample;

final class DeleteAssayAction
{
    public function execute(Assay $assay): void
    {
        $assay->delete();
        Result::whereDoesntHave('measurements')->delete();
        Sample::whereDoesntHave('results')->delete();
    }
}
