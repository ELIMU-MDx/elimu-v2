<?php

declare(strict_types=1);

namespace App\Admin\Experiments\Controllers;

use Domain\Assay\Models\AssayParameter;
use Domain\Experiment\DataTransferObjects\ExperimentListItem;
use Domain\Experiment\DataTransferObjects\ExperimentTarget;
use Domain\Experiment\DataTransferObjects\ExperimentTargetQuantification;
use Domain\Experiment\Enums\ImportStatus;
use Domain\Experiment\Models\Experiment;
use Domain\Experiment\Models\Measurement as MeasurementModel;
use Domain\Experiment\Models\QuantifyParameter;
use Domain\Rdml\Collections\MeasurementCollection;
use Domain\Rdml\DataTransferObjects\Measurement;
use Domain\Rdml\Enums\MeasurementType;
use Domain\Results\DataTransferObjects\Result;
use Domain\Results\DataTransferObjects\ResultValidationParameter;
use Domain\Results\ResultValidationErrors\ControlValidationError;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Spatie\LaravelData\DataCollection;
use Support\ValueObjects\Percentage;
use Support\ValueObjects\RoundedNumber;

final class ListExperimentsController
{
    public function __invoke(Guard $guard): View
    {
        $experiments = Experiment::where('study_id', $guard->user()->study_id)
            ->withSamplesCount()
            ->with('controls', 'quantifyParameters', 'assay.parameters')
            ->orderByDesc('created_at')
            ->get();

        $items = $experiments->map(function (Experiment $experiment) {
            $quantificationParameters = $experiment->quantifyParameters->keyBy('target');

            return new ExperimentListItem(
                experimentId: $experiment->id,
                studyId: $experiment->study_id,
                name: $experiment->name,
                importPending: $experiment->import_status === ImportStatus::PENDING,
                numberOfSamples: $experiment->count_samples,
                assay: $experiment->assay->name,
                runDate: $experiment->experiment_date->toImmutable(),
                uploadedDate: $experiment->created_at->toImmutable(),
                targets: new DataCollection(ExperimentTarget::class,
                    $experiment->assay->parameters->map(fn(AssayParameter $parameter) => new ExperimentTarget(
                        name: $parameter->target,
                        errors: $this->getControlErrors($parameter,
                            $experiment->controls->where('target', $parameter->target))->toArray(),
                        quantification: $this->getTargetQuantification($parameter,
                            $quantificationParameters->get($parameter->target))
                    )
                    )),
                eln: $experiment->eln
            );
        })->toBase();

        return view('admin.experiments.index', ['experiments' => $items]);
    }

    private function getTargetQuantification(
        AssayParameter $parameter,
        ?QuantifyParameter $quantifyParameter
    ): ?ExperimentTargetQuantification {
        if ($quantifyParameter) {
            return new ExperimentTargetQuantification(
                formula: "y = {$quantifyParameter->slope}x + {$quantifyParameter->intercept}",
                e: $this->calculateE($quantifyParameter->slope),
                squareCorrelationCoefficient: new RoundedNumber($quantifyParameter->correlation_coefficient ** 2, 3),
            );
        }

        if (!$parameter->slope) {
            return null;
        }

        return new ExperimentTargetQuantification(
            formula: "y = {$parameter->slope}x + {$parameter->intercept}",
            e: $this->calculateE($parameter->slope),
        );
    }

    private function calculateE(float $slope): Percentage
    {
        return new Percentage((1 - (10 ** (-1 / $slope))) * -1, 2);
    }

    /**
     * @param  AssayParameter  $parameter
     * @param  Collection  $controls
     * @return Collection<string> error messages
     * @throws \Spatie\DataTransferObject\Exceptions\UnknownProperties
     */
    private function getControlErrors(AssayParameter $parameter, Collection $controls): Collection
    {
        $validator = app(ControlValidationError::class);
        $errors = [];

        if ($parameter->ntc_control !== null && !$controls->firstWhere('type', MeasurementType::NTC_CONTROL())) {
            $errors[] = 'No ntc control found';
        }
        if ($parameter->negative_control !== null && !$controls->firstWhere('type',
                MeasurementType::NEGATIVE_CONTROL())) {
            $errors[] = 'No negative control found';
        }
        if ($parameter->positive_control !== null && !$controls->firstWhere('type',
                MeasurementType::POSTIVE_CONTROL())) {
            $errors[] = 'No positive control found';
        }

        return $controls
            ->map(fn(MeasurementModel $measurement) => Measurement::fromModel($measurement))
            ->pipeInto(MeasurementCollection::class)
            ->groupBy('type')
            ->map(function (MeasurementCollection $measurements) use ($parameter, $validator) {
                $result = new Result([
                    'sample' => $measurements->first()->sample,
                    'target' => $measurements->first()->target,
                    'averageCQ' => $measurements->averageCq(),
                    'repetitions' => $measurements->count(),
                    'qualification' => $measurements->qualify((float) $parameter->cutoff),
                    'measurements' => $measurements,
                    'type' => $measurements->first()->type,
                ]);
                $resultParameter = ResultValidationParameter::fromModel($parameter);

                if (!$validator->validate($result, $resultParameter)) {
                    return $validator->message($result, $resultParameter);
                }

                return null;
            })
            ->filter()
            ->toBase()
            ->merge($errors)
            ->values();
    }
}

