<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\Assay;
use App\Models\User;
use Domain\Assay\Actions\CreateAssayFromFileAction;
use Domain\Experiment\Actions\CreateExperimentAction;
use Domain\Experiment\DataTransferObjects\CreateExperimentParameter;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use JsonException;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Validators\Failure;
use Maatwebsite\Excel\Validators\ValidationException as ExcelValidationException;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;

/**
 * @property-read User user
 */
final class CreateExperimentForm extends Component
{
    use AuthorizesRequests, WithFileUploads;

    /** @var bool */
    public $openModal = false;

    /** @var array */
    public $assays;

    /** @var TemporaryUploadedFile */
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
                    ->flatMap(fn (Failure $failure, string $key) => collect($failure->errors())
                        ->mapWithKeys(fn (string $errorMessage, string|int $errorKey) => [
                            'assayFile.'.$key.$errorKey => sprintf(
                                'Row %s (%s): %s',
                                $failure->row(),
                                $failure->attribute(),
                                $errorMessage
                            ),
                        ])->toArray())->toArray(),
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
     * @throws UnknownProperties
     * @throws JsonException
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
