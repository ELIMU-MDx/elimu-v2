<?php

declare(strict_types=1);

namespace App\View\Livewire;

use Domain\Assay\Models\Assay;
use Domain\Evaluation\Collections\SampleDataCollection;
use Domain\Evaluation\DataTransferObjects\SampleData;
use Domain\Evaluation\DataTransferObjects\TargetData;
use Domain\Evaluation\Validators\SampleValidator;
use Domain\Rdml\Converters\RdmlConverter;
use Domain\Rdml\DataTransferObjects\Rdml;
use Domain\Rdml\DataTransferObjects\Target;
use Domain\Rdml\Services\RdmlFileReader;
use Domain\Rdml\Services\RdmlParser;
use Domain\Results\DataTransferObjects\ResultValidationParameter;
use Domain\Results\Enums\QualitativeResult;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\TemporaryUploadedFile;
use Livewire\WithFileUploads;
use Support\RoundedNumber;

class DemoForm extends Component
{
    use WithFileUploads;

    public mixed $assays = null;

    public mixed $selectedAssay = null;

    public mixed $rdml = null;

    public array $targets = [];

    public ?Collection $result = null;

    public function render(): View
    {
        return view('livewire.demo-form');
    }

    public function getCurrentAssayProperty()
    {
        return $this->assays->firstWhere('id', $this->selectedAssay);
    }

    public function addTarget(string $name, string $fluor): void
    {
        $this->targets[strtolower($name)] = [
            'target' => $name,
            'fluor' => $fluor,
            'quantify' => false,
            'cutoff' => '',
            'cutoff_stddev' => '',
            'slope' => '',
            'intercept' => '',
            'repetitions' => '1',
        ];
    }

    public function updatedRdml(TemporaryUploadedFile $file): void
    {
        $this->validate([
            'rdml' => 'mimes:rdml,zip',
        ]);

        $rdml = $this->getRdml($file);

        $this->targets = [];

        $query = Assay::with('parameters')->orderBy('name');

        $rdml->targets->each(function (Target $target) use ($query): void {
            $query->whereHas('parameters', function (Builder $query) use ($target) {
                return $query->where('target', strtolower($target->id));
            });
            $this->addTarget($target->id, $target->dye);
        });

        $this->assays = $query->get();
    }

    public function updatedSelectedAssay(): void
    {
        /** @var Assay $assay */
        $assay = $this->assays->firstWhere('id', $this->selectedAssay);

        if (!$assay) {
            foreach ($this->targets as $target => $data) {
                $this->targets[$target]['cutoff'] = '';
                $this->targets[$target]['cutoff_stddev'] = '';
                $this->targets[$target]['quantify'] = false;
                $this->targets[$target]['slope'] = '';
                $this->targets[$target]['intercept'] = '';
                $this->targets[$target]['repetitions'] = '1';
            }

            return;
        }

        foreach ($assay->parameters as $parameter) {
            if (!isset($this->targets[$parameter->target])) {
                continue;
            }
            $this->targets[$parameter->target]['cutoff'] = $parameter->cutoff;
            $this->targets[$parameter->target]['cutoff_stddev'] = $parameter->standard_deviation_cutoff;
            $this->targets[$parameter->target]['quantify'] = $parameter->slope && $parameter->intercept;
            $this->targets[$parameter->target]['slope'] = (new RoundedNumber($parameter->slope))->toString();
            $this->targets[$parameter->target]['intercept'] = (new RoundedNumber($parameter->intercept))->toString();
            $this->targets[$parameter->target]['repetitions'] = $parameter->required_repetitions;
        }
    }

    public function analyze(): void
    {
        $this->validate([
            'targets.*.cutoff' => 'required|numeric',
            'targets.*.cutoff_stddev' => 'required|numeric',
            'targets.*.quantify' => 'required|boolean',
            'targets.*.slope' => 'required_with:quantify|numeric',
            'targets.*.intercept' => 'required_with:quantify|numeric',
            'targets.*.repetitions' => 'required|integer|min:1',
        ], attributes: [
            'targets.*.cutoff' => 'cutoff',
            'targets.*.cutoff_stddev' => 'cutoff standard deviation',
            'targets.*.quantify' => 'quantify',
            'targets.*.slope' => 'slope',
            'targets.*.intercept' => 'intercept',
            'targets.*.repetitions' => 'repetitions',
        ]);

        $rdml = $this->getRdml($this->rdml);

        $data = (new RdmlConverter($rdml))->toSampleData();

        $data = $data->map(function (SampleData $sampleData) {
            $sampleData->targets = $sampleData->targets->map(function (TargetData $targetData) {
                $targetData->errors = (new SampleValidator())->validate(
                    $targetData->dataPoints,
                    $this->getValidationParameterForTarget($targetData->id)
                );

                return $targetData;
            });

            return $sampleData;
        });

        $this->result = $this->evaluateResults($data);
    }

    private function getValidationParameterForTarget(string $target): ResultValidationParameter
    {
        $target = strtolower($target);

        return new ResultValidationParameter([
            'requiredRepetitions' => (int) $this->targets[$target]['repetitions'] ?: 1,
            'cutoff' => (float) $this->targets[$target]['cutoff'],
            'standardDeviationCutoff' => (float) $this->targets[$target]['cutoff_stddev'],
        ]);
    }

    private function getRdml(TemporaryUploadedFile $file): Rdml
    {
        return app(RdmlParser::class)->extract(app(RdmlFileReader::class)->read($file));
    }

    private function evaluateResults(SampleDataCollection $data): Collection
    {
        return $data->map(function (SampleData $sampleData) {
            return (object) [
                'id' => $sampleData->id,
                'targets' => $sampleData->targets->map(function (TargetData $targetData) {
                    $target = strtolower($targetData->id);
                    $qualification = $targetData->dataPoints->qualify($this->targets[$target]['cutoff']);

                    return (object) [
                        'id' => $targetData->id,
                        'cq' => $targetData->dataPoints->averageCq(),
                        'quantification' => $this->targets[$target]['slope'] && $this->targets[$target]['intercept'] && $qualification === QualitativeResult::POSITIVE()
                            ? $targetData->dataPoints->quantify(
                                (float) $this->targets[$target]['slope'],
                                (float) $this->targets[$target]['intercept']
                            )
                            : null,
                        'qualification' => $targetData->dataPoints->qualify($this->targets[$target]['cutoff']),
                        'standardDeviation' => $targetData->dataPoints->count() > 1 ? $targetData->dataPoints->standardDeviationCq() : null,
                        'replications' => $targetData->dataPoints->count(),
                        'errors' => $targetData->errors,
                    ];
                }),
            ];
        })->toBase();
    }
}
