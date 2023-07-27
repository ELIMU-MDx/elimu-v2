<?php

namespace App\Http\Controllers;

use App\Models\Assay;
use App\Models\Sample;
use Domain\Study\Actions\CreateSampleReportAction;

final class ShowSampleReportController
{
    public function __construct(readonly private CreateSampleReportAction $createReport)
    {

    }

    public function __invoke(Assay $assay, Sample $sample)
    {
        $report = $this->createReport->execute($assay, $sample);

        return view('samples.report', compact('report'));
    }
}
