<?php

namespace App\Admin\Experiments\Controllers;

use App\Admin\Data\SampleReportData;
use App\Admin\Data\SampleReportTarget;
use Domain\Assay\Models\Assay;
use Domain\Assay\Models\AssayParameter;
use Domain\Experiment\Models\Sample;

final class ShowSampleReportController
{
    public function __invoke(Assay $assay, Sample $sample)
    {

        new SampleReportData(
            sampleId: $sample->identifier,
            assayName: $assay->name,
            targets: $assay->parameters
                ->reject(fn(AssayParameter $parameter) => $parameter->is_control)
            ->map(fn(AssayParameter $parameter) => new SampleReportTarget())
        );
        return view('samples.report', compact('sample', 'assay'));
    }
}
