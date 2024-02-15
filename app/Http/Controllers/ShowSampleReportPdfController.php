<?php

namespace App\Http\Controllers;

use App\Models\Assay;
use App\Models\Sample;
use Domain\Study\Actions\CreateSampleReportAction;
use Spatie\Browsershot\Browsershot;

final class ShowSampleReportPdfController
{
    public function __construct(readonly private CreateSampleReportAction $createReport)
    {
    }

    public function __invoke(Assay $assay, Sample $sample)
    {
        $report = $this->createReport->execute($assay, $sample);

        return response(
            Browsershot::html(view('samples.report', compact('report'))->render())
                ->pages('1-2')
                ->emulateMedia('print')
                ->pdf()
        )
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="sample-report.pdf"');
    }
}
