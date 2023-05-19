<?php

namespace App\Http\Livewire;

use App\Models\AssayParameter;
use Domain\Assay\Importers\AssayParameterExcelImporter;
use Domain\Rdml\DataTransferObjects\Measurement;
use Domain\Rdml\Enums\MeasurementType;
use Domain\Rdml\RdmlReader;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\TemporaryUploadedFile;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Excel;
use Maatwebsite\Excel\Facades\Excel as ExcelFacade;
use Throwable;

class DemoForm extends Component
{
    use WithFileUploads;

    /** @var TemporaryUploadedFile */
    public $assay = null;

    /** @var TemporaryUploadedFile */
    public $rdml = null;

    protected array $rules = [
        'rdml' => 'required|file|mimes:rdml,zip',
        'assay' => 'required|file|mimes:xlsx',
    ];

    public function render(): View
    {
        return view('livewire.demo-form');
    }

    public function updatedRdml(TemporaryUploadedFile $rdml): void
    {
        $this->validateOnly('rdml');

        try {
            app(RdmlReader::class)->read($this->rdml);
        } catch (Exception) {
            $this->addError('rdml', 'Could not parse rdml file');
        }
    }

    public function updatedAssay(TemporaryUploadedFile $assay): void
    {
        $this->validateOnly('assay');

        ExcelFacade::toCollection(new AssayParameterExcelImporter(1), $this->assay->getRealPath());
    }

    public function getAssayParametersProperty(Excel $excel): Collection
    {
        return $excel->toCollection(
            new AssayParameterExcelImporter(1),
            $this->assay->getRealPath()
        )
            ->flatten(1)
            ->map(fn (Collection $parameter) => new AssayParameter([
                'target' => $parameter->get('target'),
                'cutoff' => $parameter->get('cutoff'),
                'standard_deviation_cutoff' => $parameter->get('cutoff_standard_deviation'),
                'required_repetitions' => $parameter->get('required_repetitions'),
                'slope' => $parameter->get('slope'),
                'intercept' => $parameter->get('intercept'),
            ]));
    }

    public function getMeasurementsProperty(RdmlReader $rdmlReader)
    {
        if (! $this->assay || ! $this->rdml) {
            return [];
        }

        try {
            return $rdmlReader->read($this->rdml)->measurements->filter(fn (Measurement $measurement) => $measurement->type === MeasurementType::SAMPLE);
        } catch (Throwable) {
            return [];
        }
    }
}
