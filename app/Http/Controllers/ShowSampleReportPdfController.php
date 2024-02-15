<?php

namespace App\Http\Controllers;

use App\Models\Assay;
use App\Models\Sample;
use Spatie\Browsershot\Browsershot;
use URL;

final class ShowSampleReportPdfController
{
    public function __invoke(Assay $assay, Sample $sample)
    {
        return Browsershot::url(URL::temporarySignedRoute('samples.report',
            now()->addMinutes(5), [
                'sample' => $sample,
                'assay' => $assay,
            ]))
            ->pages('1')
            ->emulateMedia('print')
            ->format('A4')
            ->pdf();

    }
}
