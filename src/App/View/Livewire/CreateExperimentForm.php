<?php

declare(strict_types=1);

namespace App\View\Livewire;

use Domain\Assay\Actions\CreateAssayFromFileAction;
use Domain\Assay\Models\Assay;
use Domain\Experiment\Actions\CreateExperimentAction;
use Domain\Experiment\DataTransferObjects\CreateExperimentParameter;
use Domain\Users\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
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
    use WithFileUploads, AuthorizesRequests;

    /** @var bool */
    public $openModal = false;

    /** @var array */
    public $assays;

    /** @var \Livewire\TemporaryUploadedFile */
    public $assayFile = null;

    /** @var array */
    public $form = [
        'rdml' => [],
        'assay_id' => null,
        'eln' => null,
    ];

    public function mount()
    {
        $this->assays = Assay::where('study_id', $this->user->study_id)
            ->pluck('name', 'id')
            ->toArray();

        $this->form['assay_id'] = array_key_first($this->assays);
    }

    public function getUserProperty(): User
    {
        return Auth::user();
    }

    public function updatedAssayFile(TemporaryUploadedFile $assayFile)
    {
        $this->authorize('import-rdml');
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
                                    'assayFile.'.$key.$errorKey => sprintf(
                                        'Row %s (%s): %s',
                                        $failure->row(),
                                        $failure->attribute(),
                                        $errorMessage
                                    ),
                                ];
                            })->toArray();
                    })->toArray(),
            ]);
        }
        $this->assayFile = null;
        $this->assays = collect($this->assays)
            ->put($newAssay->id, $newAssay->name)
            ->sort()
            ->toArray();
        $this->form['assay_id'] = $newAssay->id;
    }

    /**
     * @throws \Spatie\DataTransferObject\Exceptions\UnknownProperties
     * @throws \JsonException
     */
    public function createExperiment(CreateExperimentAction $createExperimentAction): mixed
    {
        $this->authorize('import-rdml');
        $this->validate([
            'form.rdml.*' => 'required|file|mimetypes:application/zip',
            'form.assay_id' => [
                'required', Rule::exists('assays', 'id')->where('study_id', $this->user->study_id),
            ],
            'form.eln' => ['nullable', 'string', 'max:255'],
        ], [], [
            'form.assay_id' => 'assay',
            'form.eln' => 'ELN',
            'form.rdml' => 'rdml file',
        ]);

        collect($this->form['rdml'])->each(fn ($file) => $createExperimentAction->execute(new CreateExperimentParameter(
            rdml: $file,
            assayId: $this->form['assay_id'],
            studyId: $this->user->study_id,
            creatorId: $this->user->id,
            eln: $this->form['eln']
        )));

        $this->reset('form', 'openModal');
        $this->resetErrorBag();

        return redirect()->to(route('experiments.index'));
    }

    public function render(): View
    {
        return view('admin.experiments.create-experiment-button');
    }
}
