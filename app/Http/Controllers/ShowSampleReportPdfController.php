<?php

namespace App\Http\Controllers;

use App\Models\Assay;
use App\Models\Sample;
use Illuminate\Support\Facades\URL;
use Spatie\Browsershot\Browsershot;

final class ShowSampleReportPdfController
{
    public function __invoke(Assay $assay, Sample $sample)
    {
        return response(
            Browsershot::url(
                URL::temporarySignedRoute('samples.report', now()->addMinute(), compact('sample', 'assay'))
            )
                ->pages('1')
                ->emulateMedia('print')
                ->pdf()
        )
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="sample-report.pdf"');
    }
}