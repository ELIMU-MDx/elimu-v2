<?php

namespace App\View\ViewModels;

use App\Models\AssayParameter;
use App\Models\Experiment;
use App\Models\Measurement as MeasurementModel;
use App\Models\QuantifyParameter;
use Domain\Experiment\DataTransferObjects\ExperimentListItem;
use Domain\Experiment\DataTransferObjects\ExperimentTarget;
use Domain\Experiment\DataTransferObjects\ExperimentTargetQuantification;
use Domain\Experiment\Enums\ImportStatus;
use Domain\Rdml\Collections\MeasurementCollection;
use Domain\Rdml\DataTransferObjects\Measurement;
use Domain\Rdml\Enums\MeasurementType;
use Domain\Results\DataTransferObjects\Result;
use Domain\Results\DataTransferObjects\ResultValidationParameter;
use Domain\Results\ResultValidationErrors\ControlValidationError;
use Illuminate\Support\Collection;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;
use Support\ValueObjects\Percentage;
use Support\ValueObjects\RoundedNumber;

final class ExperimentList
{
    public function __construct(private readonly Collection $experiments)
    {
    }

    public function experiments(): Collection
    {
        return $this->experiments->map(function (Experiment $experiment) {
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
                targets: $experiment->assay->parameters->map(fn (AssayParameter $parameter) => new ExperimentTarget(
                    name: $parameter->target,
                    errors: $this->getControlErrors($parameter,
                        $experiment->controls->where('target', $parameter->target))->toArray(),
                    quantification: $this->getTargetQuantification($parameter,
                        $quantificationParameters->get($parameter->target))
                )
                ),
                eln: $experiment->eln,
                instrument: $experiment->instrument
            );
        })->toBase();
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

        if (! $parameter->slope) {
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
     * @return Collection<string> error messages
     *
     * @throws UnknownProperties
     */
    private function getControlErrors(AssayParameter $parameter, Collection $controls): Collection
    {
        $validator = app(ControlValidationError::class);
        $errors = [];

        if (! blank($parameter->ntc_control) && ! $controls->firstWhere('type', MeasurementType::NTC_CONTROL)) {
            $errors[] = 'No ntc control found';
        }
        if (! blank($parameter->negative_control) && ! $controls->firstWhere('type',
            MeasurementType::NEGATIVE_CONTROL)) {
            $errors[] = 'No negative control found';
        }
        if (! blank($parameter->positive_control) && ! $controls->firstWhere('type',
            MeasurementType::POSTIVE_CONTROL)) {
            $errors[] = 'No positive control found';
        }

        return $controls
            ->map(fn (MeasurementModel $measurement) => Measurement::fromModel($measurement))
            ->pipeInto(MeasurementCollection::class)
            ->groupBy(fn (Measurement $measurement) => $measurement->type->name)
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

                if (! $validator->validate($result, $resultParameter)) {
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
