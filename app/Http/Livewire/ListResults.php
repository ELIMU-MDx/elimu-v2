<?php

declare(strict_types=1);

namespace App\Http\Livewire;

use App\Models\Assay;
use App\Models\Sample;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithPagination;

final class ListResults extends Component
{
    use WithPagination;

    /** @var Collection<Assay> */
    public $assays;

    /** @var int */
    public $currentAssayId;

    /** @var int|null */
    public $currentlyEditingSampleKey = null;

    /** @var string */
    public $search = '';

    /** @var string */
    public $resultFilter = 'all';

    /** @var string */
    public $targetFilter = 'all';

    /** @var array<string> */
    protected $rules = [
        'assay' => 'required|boolean',
    ];

    protected $queryString = ['search' => ['except' => ''], 'currentAssayId' => ['except' => ''], 'resultFilter' => ['except' => 'all'], 'targetFilter' => ['except' => 'all']];

    public function mount(): void
    {
        $this->assays = Assay::whereHas('results')
            ->where('study_id', Auth::user()->study_id)
            ->with([
                'parameters' => fn($query) => $query->orderBy('target'),
            ])
            ->get();

        if ($this->assays->isEmpty()) {
            return;
        }

        $this->currentAssayId = $this->assays->first()->id;
        $this->queryString['currentAssayId'] = ['except' => $this->assays->first()->id];
    }

    public function updating(): void
    {
        $this->resetPage();
    }

    public function getCurrentAssayProperty(): ?Assay
    {
        return $this->assays->firstWhere('id', $this->currentAssayId);
    }

    public function getCurrentTargetsProperty(): array
    {
        return $this->currentAssay
            ->parameters
            ->pluck('target')
            ->sort()
            ->toArray();
    }

    public function getSamplesProperty(): LengthAwarePaginator
    {
        if (! $this->currentAssayId) {
            return new LengthAwarePaginator([], 0, 15);
        }

        return Sample::listAll($this->currentAssayId, Auth::user()->study_id)
            ->searchBySampleIdentifier($this->search)
            ->filterByResult($this->resultFilter, $this->targetFilter)
            ->paginate();
    }

    public function getTotalSamplesProperty(): int
    {
        if (! $this->currentAssayId) {
            return 0;
        }

        return Sample::countAll($this->currentAssayId, Auth::user()->study_id)
            ->count();
    }

    public function render(): View
    {
        return view('admin.results.list-results');
    }
}
