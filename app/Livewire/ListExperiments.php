<?php

namespace App\Livewire;

use App\Models\Assay;
use App\Models\Experiment;
use App\View\ViewModels\ExperimentList;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

final class ListExperiments extends Component
{
    public $assays = [];

    public $instruments = [];

    public $filter = [
        'assay' => null,
        'device' => null,
    ];

    public $sort = 'upload_date';

    protected $queryString = ['filter', 'sort'];

    public function mount()
    {
        $this->assays = Assay::where('study_id', Auth::user()->study_id)->pluck('name', 'id');
        $this->instruments = Experiment::where('study_id', Auth::user()->study_id)->pluck('instrument', 'instrument');
    }

    public function getExperimentsProperty(): Collection
    {
        $sort = [
            'run_at' => [
                'field' => 'experiment_date',
                'direction' => 'desc',
            ],
            'name' => [
                'field' => 'name',
                'direction' => 'asc',
            ],
        ][$this->sort] ?? [
            'field' => 'created_at',
            'direction' => 'desc',
        ];

        $experiments = Experiment::query()
            ->where('study_id', Auth::user()->study_id)
            ->withSamplesCount()
            ->with('controls', 'quantifyParameters', 'assay.parameters')
            ->orderBy($sort['field'], $sort['direction'])
            ->get();

        return (new ExperimentList($experiments))->experiments();
    }

    public function updateSort(string $sortBy): void
    {
        $this->sort = $sortBy;
    }

    public function render(): View
    {
        return view('admin.experiments.list-experiments');
    }
}
