<?php

declare(strict_types=1);

namespace App\View\Livewire;

use Domain\Assay\Actions\CreateAssayFromFileAction;
use Domain\Assay\Models\Assay;
use Domain\Experiment\Actions\CreateExperimentAction;
use Domain\Users\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\TemporaryUploadedFile;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Validators\Failure;
use Maatwebsite\Excel\Validators\ValidationException as ExcelValidationException;

/**
 * @property-read User user
 */
final class CreateExperimentForm extends Component
{
    use WithFileUploads;

    /** @var bool */
    public $openModal = false;

    /** @var \Illuminate\Database\Eloquent\Collection<Assay> */
    public $assays;

    /** @var \Livewire\TemporaryUploadedFile */
    public $assayFile = null;

    /** @var array */
    public $form = [
        'rdml' => null,
        'assay_id' => null,
        'meta' => null,
    ];

    public function mount()
    {
        $this->assays = Assay::where('study_id', $this->user->study_id)
            ->pluck('name', 'id');
    }

    public function getUserProperty(): User
    {
        return Auth::user();
    }

    public function updatedAssayFile(TemporaryUploadedFile $assayFile)
    {
        $this->validate(['assayFile' => ['file', 'mimes:xlsx']]);

        try {
            $newAssay = app(CreateAssayFromFileAction::class)->execute($assayFile, Auth::user());
        } catch (ExcelValidationException $exception) {
            throw ValidationException::withMessages([
                'assayFile' => collect($exception->failures())
                    ->flatMap(function (Failure $failure, string $key) {
                        return collect($failure->errors())
                            ->mapWithKeys(function (string $errorMessage, string|int $errorKey) use ($failure, $key) {
                                return [
                                    'assayFile.'.$key.$errorKey => sprintf('Row %s (%s): %s', $failure->row(),
                                        $failure->attribute(), $errorMessage),
                                ];
                            })->toArray();
                    })->toArray(),
            ]);
        }
        $this->assays = $this->assays
            ->put($newAssay->id, $newAssay->name)
            ->sort();
        $this->form['assay_id'] = $newAssay->id;
    }

    /**
     * @throws \Spatie\DataTransferObject\Exceptions\UnknownProperties
     * @throws \JsonException
     */
    public function createExperiment(CreateExperimentAction $createExperimentAction): mixed
    {
        $this->validate([
            'form.rdml' => 'required|file|mimetypes:application/zip',
            'form.assay_id' => [
                'required_without:form.assay', Rule::exists('assays', 'id')->where('study_id', $this->user->study_id),
            ],
            'form.assay' => ['nullable', 'file', 'mimes:xlsx'],
            'form.meta' => ['nullable', 'file', 'mimes:xlsx'],
        ], [
            'form.assay.required_without' => 'You either need to choose an assay or import a new one',
        ], [
            'form.assay_id' => 'assay',
            'form.assay' => 'assay',
            'form.meta' => 'meta',
            'form.rdml' => 'rdml file',
        ]);

        $createExperimentAction->execute(
            $this->form['rdml'],
            Assay::find($this->form['assay_id']),
            $this->user->study_id,
            $this->user->id
        );
        $this->reset('form', 'openModal');
        $this->resetErrorBag();

        return redirect()->to(route('results.index'));
    }

    public function render(): View
    {
        return view('admin.experiments.create-experiment-button');
    }
}
