<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\Study;
use Domain\Study\Actions\CreateStudyAction;
use Domain\Study\DataTransferObject\CreateStudyParameter;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

final class StudyForm extends Component
{
    public $study;

    protected $rules = [
        'study.identifier' => 'required|string|max:255',
        'study.name' => 'required|string|max:255',
    ];

    public function mount(?Study $study = null)
    {
        $this->study = $study ?? new Study();
    }

    public function render(): View
    {
        return view('studies.study-form');
    }

    public function saveStudy(CreateStudyAction $createStudyAction)
    {
        $this->validate();

        if (! $this->study->exists) {
            $createStudyAction->execute(Auth::getUser(), new CreateStudyParameter(
                identifier: $this->study->identifier,
                name: $this->study->name
            ));

            return redirect()->to(route('currentStudy.show'));
        }

        $this->study->save();
        $this->dispatch('saved');
    }
}
