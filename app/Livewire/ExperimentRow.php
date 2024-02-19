<?php

declare(strict_types=1);

namespace App\Livewire;

use Domain\Experiment\Actions\DeleteExperimentAction;
use Domain\Experiment\DataTransferObjects\ExperimentListItem;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Routing\Redirector;
use Livewire\Component;

final class ExperimentRow extends Component
{
    use AuthorizesRequests;

    public ExperimentListItem $experiment;

    /** @var bool */
    public $showDeleteConfirmationModal = false;

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
