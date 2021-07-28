<?php

declare(strict_types=1);

namespace App\View\Livewire;

use Domain\Assay\Actions\DeleteAssayAction;
use Domain\Assay\Models\Assay;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\Redirector;

final class AssayRow extends Component
{
    use AuthorizesRequests;

    public $assay;

    /** @var bool */
    public $showDeleteConfirmationModal = false;

    public function mount(Assay $assay)
    {
        $this->assay = $assay;
    }

    public function deleteAssay(DeleteAssayAction $action): Redirector
    {
        $this->authorize('delete-assay', $this->assay);
        $action->execute($this->assay);

        return redirect(route('assays.index'));
    }

    public function render(): View
    {
        return view('admin.assays.assay-row');
    }
}
