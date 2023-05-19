<?php

namespace App\Http\Livewire;

use App\Models\AssayParameter;
use Domain\Experiment\DataTransferObjects\ResultCalculationParameter;
use Domain\Rdml\DataTransferObjects\Measurement;
use Domain\Rdml\Enums\MeasurementType;
use Domain\Results\DataTransferObjects\Result;
use Domain\Results\DataTransferObjects\ResultValidationParameter;
use Domain\Results\MeasurementEvaluator;
use Domain\Results\ResultValidationErrors\ResultValidationErrorFactory;
use Domain\Results\ResultValidator;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithFileUploads;

class DemoFormRow extends Component
{
    use WithFileUploads;

    /** @var bool */
    public $even = false;

    /** @var Collection */
    public $measurements;

    /** @var string */
    public $sample;

    /** @var Collection */
    public $calculationParameters;

    /** @var array */
    public $validationParameters;

    public function mount(
        bool $even,
        string $sample,
        Collection $measurements,
        Collection $assayParameters,
    ) {
        $this->even = $even;
        $this->sample = $sample;
        $this->measurements = $measurements;
        $this->calculationParameters = $assayParameters->map(fn(AssayParameter $parameter) => ResultCalculationParameter::fromModel($parameter));
        $this->validationParameters = $assayParameters->mapWithKeys(fn(AssayParameter $parameter) => [
            $parameter->target => ResultValidationParameter::fromModel($parameter),
        ])->toArray();
    }

    public function hydrateCalculationParameters($calculationParameters): void
    {
        $this->calculationParameters = $calculationParameters->map(fn (array $parameter) => new ResultCalculationParameter($parameter));
    }

    public function hydrateValidationParameters($validationParameters): void
    {
        $this->validationParameters = collect($validationParameters)
            ->map(fn (array $parameter) => new ResultValidationParameter($parameter))
            ->toArray();
    }

    public function hydrateMeasurements($measurements): void
    {
        $this->measurements = $measurements->map(function (array $measurement) {
            $measurement['type'] = MeasurementType::from($measurement['type']);

            return new Measurement($measurement);
        });
    }

    public function render(): View
    {
        return view('livewire.demo-form-row');
    }

    public function toggleExcluded(string $target, int $measurementKey): void
    {
        /** @var Measurement|null $measurement */
        $measurement = $this->measurements
            ->groupBy('target')
            ->get($target)
            ?->get($measurementKey);

        if (! $measurement) {
            return;
        }

        $key = $this->measurements->search($measurement);
        $measurement->excluded = ! $measurement->excluded;
        $this->measurements->put($key, $measurement);
    }

    public function getResultsProperty(MeasurementEvaluator $measurementEvaluator, ResultValidator $resultValidator)
    {
        return $measurementEvaluator->results(
            $this->measurements,
            $this->calculationParameters
        )
            ->map(function (Result $result) use ($resultValidator) {
                $errors = $resultValidator->validate(
                    $result,
                    $this->validationParameters[strtolower($result->target)]
                )
                    ->map(fn(string $errorIdentifier) => ResultValidationErrorFactory::get($errorIdentifier)->message(
                        $result,
                        $this->validationParameters[strtolower($result->target)]
                    ));

                return (object) array_merge(
                    $result->toArray(),
                    ['errors' => $errors]
                );
            })->sortBy('target');
    }
}
