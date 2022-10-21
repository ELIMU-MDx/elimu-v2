<?php

namespace App\Admin\Experiments\Controllers;

use Domain\Experiment\Models\Sample;

final class ShowSampleReportController
{
    public function __invoke(Sample $sample)
    {
        return view('samples.report', compact('sample'));
    }
}
