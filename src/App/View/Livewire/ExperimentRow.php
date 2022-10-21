<?php

declare(strict_types=1);

namespace App\View\Livewire;

use Domain\Experiment\Actions\DeleteExperimentAction;
use Domain\Experiment\DataTransferObjects\ExperimentListItem;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\Redirector;

final class ExperimentRow extends Component
{
    use AuthorizesRequests;

    /** @var ExperimentListItem */
    public $experiment;

    /** @var bool */
    public $showDeleteConfirmationModal = false;

    public function mount(ExperimentListItem $experiment)
    {
        $this->experiment = $experiment;
    }

    public function deleteExperiment(DeleteExperimentAction $action): Redirector
    {
        $this->authorize('delete-experiment', $this->experiment);
        $action->execute($this->experiment->experimentId);

        return redirect(route('experiments.index'));
    }

    public function render(): View
    {
        return view('admin.experiments.experiment-row');
    }
}
