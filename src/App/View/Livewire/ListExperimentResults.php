<?php

declare(strict_types=1);

namespace App\View\Livewire;

use Auth;
use Domain\Assay\Models\Assay;
use Domain\Experiment\Models\Sample;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\View;
use Livewire\Component;

final class ListExperimentResults extends Component
{
    /** @var Collection<\Domain\Assay\Models\Assay> */
    public $assays;

    /** @var int */
    public $currentAssayId;

    /** @var int|null */
    public $currentlyEditingSampleKey = null;

    /** @var string[] */
    protected $rules = [
        'assay' => 'required|boolean',
    ];

    public function mount(): void
    {
        $this->assays = Assay::whereHas('results')
            ->where('study_id', Auth::user()->study_id)
            ->pluck('name', 'id');

        if ($this->assays->isEmpty()) {
            return;
        }

        $this->currentAssayId = $this->assays->keys()->first();
    }

    public function getSamplesProperty(): Collection
    {
        if (!$this->currentAssayId) {
            return new Collection();
        }

        return Sample::with([
            'results.assay.parameters' => function ($query) {
                return $query->where('assay_id', $this->currentAssayId);
            }, 'results.resultErrors', 'results.measurements',
        ])
            ->where('study_id', Auth::user()->study_id)
            ->whereHas('results')
            ->orderBy('identifier')
            ->get();
    }

    public function render(): View
    {
        return view('admin.experiments.list-experiment-results');
    }
}
