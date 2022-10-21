<?php

namespace App\Admin\Experiments\Controllers;

use Domain\Experiment\Models\Sample;
use Illuminate\Support\Facades\URL;
use Spatie\Browsershot\Browsershot;

final class ShowSampleReportPdfController
{
    public function __invoke(Sample $sample)
    {
        return response(Browsershot::url(URL::temporarySignedRoute('samples.report', now()->addMinute(), compact('sample')))->pages('1')->emulateMedia('print')->pdf())
            ->header('Content-Type', 'application/pdf')
            ->header( 'Content-Disposition', 'inline; filename="sample-report.pdf"');
    }
}
