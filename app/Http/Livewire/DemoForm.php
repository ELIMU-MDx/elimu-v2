<?php

namespace App\Http\Livewire;

use Domain\Evaluation\DataTransferObjects\SampleData;
use Domain\Evaluation\DataTransferObjects\SampleValidationParameter;
use Domain\Evaluation\DataTransferObjects\TargetData;
use Domain\Evaluation\Validators\SampleValidator;
use Domain\Rdml\Converters\RdmlConverter;
use Domain\Rdml\DataTransferObjects\Rdml;
use Domain\Rdml\DataTransferObjects\Target;
use Domain\Rdml\RdmlParser;
use Domain\Rdml\RdmlReader;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\TemporaryUploadedFile;
use Livewire\WithFileUploads;

class DemoForm extends Component
{
    use WithFileUploads;

    public mixed $rdml = null;

    public array $targets = [];

    public ?Collection $result = null;

    public function render(): View
    {
        return view('livewire.demo-form');
    }

    public function addTarget(string $name, string $fluor): void
    {
        $this->targets[$name] = [
            'target' => $name,
            'fluor' => $fluor,
            'quantify' => false,
            'threshold' => '',
            'cutoff' => '',
            'cutoff_stddev' => '',
            'slope' => '',
            'intercept' => '',
            'repetitions' => '',
        ];
    }

    public function updatedRdml(TemporaryUploadedFile $file): void
    {
        $this->validate([
            'rdml' => 'mimes:rdml,zip',
        ]);

        $rdml = $this->getRdml($file);

        $this->targets = [];
        $rdml->targets->each(function (Target $target) {
            $this->addTarget($target->id, $target->dye->id);
        });
    }

    public function analyze(): void
    {
        $rdml = $this->getRdml($this->rdml);

        $data = (new RdmlConverter($rdml))->toSampleData();

        $data = $data->map(function (SampleData $sampleData) {
            $sampleData->targets = $sampleData->targets->map(function (TargetData $targetData) {
                $targetData->errors = (new SampleValidator())->validate($targetData->dataPoints,
                    $this->getValidationParameterForTarget($targetData->id));

                return $targetData;
            });

            return $sampleData;
        });

        $this->result = $data;
    }

    private function getValidationParameterForTarget(string $target)
    {
        return new SampleValidationParameter([
            'requiredRepetitions' => (int) $this->targets[$target]['repetitions'] ?: 1,
            'cutoff' => (float) $this->targets[$target]['cutoff'],
            'standardDeviationCutoff' => (float) $this->targets[$target]['cutoff_stddev'],
        ]);
    }

    private function getRdml(TemporaryUploadedFile $file): Rdml
    {
        return app(RdmlParser::class)->extract(app(RdmlReader::class)->read($file));
    }
}
