<?php

declare(strict_types=1);

namespace App\Http\Livewire;

use App\Models\Measurement;
use App\Models\Sample;
use Domain\Experiment\Actions\RecalculateResultsAction;
use Livewire\Component;

final class ResultRow extends Component
{
    /** @var Sample */
    public $sample;

    /** @var bool */
    public $even;

    /** @var array<string, string> */
    public $validationTypes;

    public function mount(bool $even, Sample $sample, array $validationTypes)
    {
        $this->even = $even;
        $this->sample = $sample;
        $this->validationTypes = $validationTypes;
    }

    public function render()
    {
        return view('admin.results.list-results-row');
    }

    public function toggleExcluded(Measurement $measurement, RecalculateResultsAction $recalculateResultsAction)
    {
        if ($measurement->sample_id !== $this->sample->id) {
            return;
        }

        $measurement->excluded = ! $measurement->excluded;
        $measurement->save();

        $measurements = $this->sample->results
            ->firstWhere('id', $measurement->result_id)
            ->measurements
            ->filter(function (Measurement $currentMeasurement) use ($measurement) {
                return $currentMeasurement->id !== $measurement->id;
            })
            ->push($measurement);

        $recalculateResultsAction->execute($measurements);
        $this->sample = $this->sample->fresh([
            'results.assay.parameters', 'results.resultErrors', 'results.measurements',
        ]);
    }
}
