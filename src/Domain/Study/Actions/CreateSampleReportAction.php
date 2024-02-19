<?php

namespace Domain\Study\Actions;

use App\Data\SampleReportData;
use App\Data\SampleReportTarget;
use App\Models\Assay;
use App\Models\AssayParameter;
use App\Models\Result;
use App\Models\Sample;
use Domain\Results\Enums\QualitativeResult;

final class CreateSampleReportAction
{
    public function execute(Assay $assay, Sample $sample): SampleReportData
    {
        $sample->load(['results' => fn ($query) => $query->withCount('resultErrors')]);
        $targets = $assay->parameters
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
            })
            ->ensure(SampleReportTarget::class);

        return new SampleReportData(
            study: $assay->study->name,
            sampleId: $sample->identifier,
            assayName: $assay->name,
            hasQuantification: (bool) $targets->first(fn (SampleReportTarget $target
            ) => $target->quantification !== null),
            result: $targets->first(fn (SampleReportTarget $target
            ) => $target->qualification === QualitativeResult::POSITIVE) ? QualitativeResult::POSITIVE : QualitativeResult::NEGATIVE,
            targets: $targets,
            controlTargets: $assay->parameters
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
                })
        );
    }
}
