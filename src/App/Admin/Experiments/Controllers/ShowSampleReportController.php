<?php

namespace App\Admin\Experiments\Controllers;

use App\Admin\Data\SampleReportData;
use App\Admin\Data\SampleReportTarget;
use chillerlan\QRCode\QRCode;
use Domain\Assay\Models\Assay;
use Domain\Assay\Models\AssayParameter;
use Domain\Experiment\Models\Sample;
use Domain\Results\Enums\QualitativeResult;
use Domain\Results\Models\Result;
use Spatie\LaravelData\DataCollection;

final class ShowSampleReportController
{
    public function __invoke(Assay $assay, Sample $sample)
    {
        $sample->load(['results' => fn ($query) => $query->withCount('resultErrors')]);
        $targets = new DataCollection(SampleReportTarget::class, $assay->parameters
            ->reject(fn (AssayParameter $parameter) => $parameter->is_control)
            ->map(function (AssayParameter $parameter) use ($assay, $sample) {
                /** @var Result $result */
                $result = $sample->results
                    ->first(fn (Result $result
                    ) => $result->assay_id === $assay->id && strcasecmp($result->target, $parameter->target) === 0);

                return new SampleReportTarget(
                    name: $parameter->description ?? $parameter->target,
                    cq: $result->cq,
                    quantification: $result->quantification,
                    qualification: $result->result_errors_count > 0 ? 'Invalid' : ucfirst(strtolower($result->qualification))
                );
            }));
        $report = new SampleReportData(
            study: $assay->study->name,
            qrCode: (new QRCode())->render(route('samples.show', $sample)),
            sampleId: $sample->identifier,
            assayName: $assay->name,
            hasQuantification: (bool) $targets->first(fn (SampleReportTarget $target
            ) => $target->quantification !== null),
            result: $targets->first(fn (SampleReportTarget $target
            ) => $target->qualification === QualitativeResult::POSITIVE()) ? QualitativeResult::POSITIVE() : QualitativeResult::NEGATIVE(),
            targets: $targets,
            controlTargets: new DataCollection(SampleReportTarget::class, $assay->parameters
                ->filter(fn (AssayParameter $parameter) => $parameter->is_control)
                ->map(function (AssayParameter $parameter) use ($assay, $sample) {
                    /** @var Result $result */
                    $result = $sample->results
                        ->first(fn (Result $result
                        ) => $result->assay_id === $assay->id && $result->target === $parameter->target);

                    return new SampleReportTarget(
                        name: $parameter->description ?? $parameter->target,
                        cq: $result->cq,
                        quantification: $result->quantification,
                        qualification: $result->result_errors_count > 0 ? 'Invalid' : ucfirst(strtolower($result->qualification))
                    );
                }))
        );

        return view('samples.report', compact('report'));
    }
}