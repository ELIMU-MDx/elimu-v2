<?php

declare(strict_types=1);

namespace App\View\Livewire;

use Auth;
use Domain\Assay\Models\Assay;
use Domain\Assay\Models\AssayParameter;
use Domain\Assay\Rules\ControlParameterValidationRule;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\View\View;
use Livewire\Component;
use Str;
use Support\DecimalValidationRule;

final class CreateAssayForm extends Component
{
    use AuthorizesRequests;

    /** @var Assay */
    public $assay;

    /** @var string */
    public $targets = '';

    /** @var bool */
    public $visible;

    /** @var Collection */
    public $parameters = [];

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
        'parameters.*.positive_control' => 'Positive Control',
        'parameters.*.negative_control' => 'Negative control',
        'parameters.*.ntc_control' => 'NTC control',
        'parameters.*.required_repetitions' => 'required_repetitions',
    ];

    public function mount(?Assay $assay = null): void
    {
        $this->assay = $assay ?? new Assay();
        $this->parameters = $this->assay->parameters ?? new Collection();
        $this->parameters->map(function (AssayParameter $parameter) {
            $parameter->quantify = $parameter->slope && $parameter->intercept;

            return $parameter;
        });
        $this->targets = $this->parameters->pluck('target')->join(', ');
        $this->visible = $this->assay->study_id ? 0 : 1;
    }

    protected function rules(): array
    {
        return [
            'assay.name' => ['required', 'string', 'max:255'],
            'assay.sample_type' => ['string', 'max:255'],
            'visible' => ['required', 'bool'],
            'targets' => ['required', 'string'],
            'parameters.*.target' => ['required', 'string'],
            'parameters.*.cutoff' => ['required', 'numeric', new DecimalValidationRule(8, 2)],
            'parameters.*.standard_deviation_cutoff' => ['required', 'numeric', new DecimalValidationRule(8, 2)],
            'parameters.*.quantify' => ['nullable', 'boolean'],
            'parameters.*.slope' => [
                'required_if:parameters.*.quantify,1', 'nullable', 'numeric', new DecimalValidationRule(8, 2),
            ],
            'parameters.*.intercept' => [
                'required_if:parameters.*.quantify,1', 'nullable', 'numeric', new DecimalValidationRule(8, 2),
            ],
            'parameters.*.positive_control' => ['nullable', new ControlParameterValidationRule()],
            'parameters.*.negative_control' => ['nullable', new ControlParameterValidationRule()],
            'parameters.*.ntc_control' => ['nullable', new ControlParameterValidationRule()],
            'parameters.*.required_repetitions' => 'required|integer|min:1',
        ];
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
                    'positive_control' => null,
                    'negative_control' => null,
                    'ntc_control' => null,
                ]);
            }));
    }

    public function saveAssay()
    {
        $this->authorize('create-assay');
        $this->validate();
        $new = $this->assay->exists;

        $parameters = $this->parameters
            ->map(function (AssayParameter $assayParameter) {
                if ($assayParameter->quantify !== '1') {
                    $assayParameter->slope = null;
                    $assayParameter->intercept = null;
                }
                unset($assayParameter->quantify);

                return $assayParameter;
            });

        $this->assay->study_id = Auth::user()->study_id;
        $this->assay->user_id = $this->assay->id ? $this->assay->user_id : Auth::user()->id;
        $this->assay->save();
        $this->assay->parameters()->saveMany($parameters);

        return redirect()->to(route('assays.index'));
    }

    public function render(): View
    {
        return view('admin.assays.create-assay-form');
    }
}
