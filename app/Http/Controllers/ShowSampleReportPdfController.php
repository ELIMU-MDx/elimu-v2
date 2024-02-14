<?php

namespace App\Http\Controllers;

use App\Models\Assay;
use App\Models\Sample;
use Domain\Study\Actions\CreateSampleReportAction;
use Spatie\LaravelPdf\Facades\Pdf;

final class ShowSampleReportPdfController
{
    public function __construct(readonly private CreateSampleReportAction $createReport)
    {
    }

    public function __invoke(Assay $assay, Sample $sample)
    {
        $report = $this->createReport->execute($assay, $sample);

        return Pdf::view('samples.report', compact('report'))
            ->format('a4');
    }
}
