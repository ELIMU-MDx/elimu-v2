<?php

declare(strict_types=1);

namespace App\View\Livewire;

use Auth;
use Domain\Assay\Models\Assay;
use Domain\Assay\Models\AssayParameter;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\View;
use Livewire\Component;
use Str;

final class CreateAssayForm extends Component
{
    /** @var Assay */
    public $assay;

    /** @var string */
    public $targets = '';

    /** @var bool */
    public $visible;

    /** @var Collection */
    public $parameters = [];

    protected $rules = [
        'assay.name' => 'required|string|max:255',
        'assay.sample_type' => 'required|string|max:255',
        'visible' => 'required|bool',
        'targets' => 'required|string',
        'parameters.*.target' => 'required|string',
        'parameters.*.cutoff' => 'required|numeric',
        'parameters.*.standard_deviation_cutoff' => 'required|numeric',
        'parameters.*.quantify' => 'nullable|boolean',
        'parameters.*.slope' => 'required_with:parameters.*.quantify|nullable|numeric',
        'parameters.*.intercept' => 'required_with:parameters.*.quantify|nullable|numeric',
        'parameters.*.required_repetitions' => 'required|integer|min:1',
    ];

    protected $validationAttributes = [
        'assay.name' => 'name',
        'targets' => 'targets',
        'visible' => 'visibility',
        'parameters.*.target' => 'target',
        'parameters.*.cutoff' => 'cutoff',
        'parameters.*.standard_deviation_cutoff' => 'cutoff standard deviation',
        'parameters.*.quantify' => 'quantity',
        'parameters.*.slope' => 'slope',
        'parameters.*.intercept' => 'intercept',
        'parameters.*.required_repetitions' => 'required_repetitions',
    ];

    public function mount(?Assay $assay = null): void
    {
        $this->assay = $assay ?? new Assay();
        $this->parameters = $this->assay->parameters ?? new Collection();
    }

    /**
     * @throws \Illuminate\Validation\ValidationException
     */
    public function updatedTargets(): void
    {
        $this->validateOnly('targets');

        if (!trim($this->targets)) {
            $this->parameters = new Collection();

            return;
        }

        $this->parameters = new Collection(Str::of($this->targets)
            ->explode(',')
            ->map(function (string $target) {
                return trim($target);
            })
            ->filter()
            ->map(function (string $target) {
                return new AssayParameter([
                    'target' => $target,
                    'cutoff' => '',
                    'standard_deviation_cutoff' => '',
                    'slope' => null,
                    'intercept' => null,
                    'required_repetitions' => 1,
                ]);
            }));
    }

    public function saveAssay()
    {
        $this->validate();

        $parameters = $this->parameters
            ->map(function (AssayParameter $assayParameter) {
                unset($assayParameter->quantify);

                return $assayParameter;
            });

        $this->assay->study_id = $this->visible ? null : Auth::user()->study_id;
        $this->assay->save();
        $this->assay->parameters()->saveMany($parameters);

        return redirect()->to(route('list-assays'));
    }

    public function render(): View
    {
        return view('admin.assays.create-assay-form');
    }
}
