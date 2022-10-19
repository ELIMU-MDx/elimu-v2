<?php

declare(strict_types=1);

namespace App\View\Livewire;

use Domain\Assay\Actions\CreateOrUpdateAssayAction;
use Domain\Assay\Models\Assay;
use Domain\Assay\Models\AssayParameter;
use Domain\Assay\Rules\ControlParameterValidationRule;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\View\View;
use Livewire\Component;
use Illuminate\Support\Str;
use Support\DecimalValidationRule;

final class CreateAssayForm extends Component
{
    use AuthorizesRequests;

    /** @var Assay */
    public $assay;

    /** @var string */
    public $targets = '';

    /** @var Collection */
    public $parameters;

    protected $validationAttributes = [
        'assay.name' => 'name',
        'targets' => 'targets',
        'parameters.*.target' => 'target',
        'parameters.*.cutoff' => 'cutoff',
        'parameters.*.standard_deviation_cutoff' => 'cutoff standard deviation',
        'parameters.*.coefficient_of_variation_cutoff' => 'cutoff coefficient of variation',
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
        $this->targets = $this->parameters->pluck('target')->join(', ');
    }

    /**
     * @throws \Illuminate\Validation\ValidationException
     */
    public function updatedTargets(): void
    {
        $this->validateOnly('targets');

        if (! trim($this->targets)) {
            $this->parameters = new Collection();

            return;
        }

        $this->parameters = new Collection(Str::of($this->targets)
            ->explode(',')
            ->map(fn (string $target) => trim($target))
            ->filter()
            ->map(function (string $target) {
                $parameter = $this->assay
                    ->parameters
                    ->first(
                        fn (AssayParameter $parameter) => strcasecmp($parameter->target, $target) === 0,
                        new AssayParameter([
                            'target' => $target,
                            'cutoff' => '',
                            'standard_deviation_cutoff' => null,
                            'coefficient_of_variation' => null,
                            'slope' => null,
                            'intercept' => null,
                            'required_repetitions' => 1,
                            'positive_control' => null,
                            'negative_control' => null,
                            'ntc_control' => null,
                        ])
                    );
                $parameter->target = $target;

                return $parameter;
            }));
    }

    public function saveAssay(CreateOrUpdateAssayAction $createOrUpdateAssayAction)
    {
        $this->authorize('create-assay');
        $this->validate();

        $createOrUpdateAssayAction->execute($this->assay, $this->parameters);

        return redirect()->to(route('assays.index'));
    }

    public function render(): View
    {
        return view('admin.assays.create-assay-form');
    }

    protected function rules(): array
    {
        return [
            'assay.name' => ['required', 'string', 'max:255'],
            'assay.sample_type' => ['string', 'max:255'],
            'targets' => ['required', 'string'],
            'parameters.*.target' => ['required', 'string'],
            'parameters.*.cutoff' => ['required', 'numeric', new DecimalValidationRule(8, 2)],
            'parameters.*.standard_deviation_cutoff' => ['required_without:parameters.*.coefficient_of_variation_cutoff', 'nullable', 'numeric', new DecimalValidationRule(8, 2)],
            'parameters.*.coefficient_of_variation_cutoff' => ['required_without:parameters.*.standard_deviation_cutoff', 'nullable', 'numeric', new DecimalValidationRule(8, 3)],
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
}
