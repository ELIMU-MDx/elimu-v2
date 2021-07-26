<?php

declare(strict_types=1);

namespace App\View\Livewire;

use Illuminate\Support\Facades\Auth;
use Laravel\Jetstream\RedirectsActions;
use Livewire\Component;

class CreateTeamForm extends Component
{
    use RedirectsActions;

    /**
     * The component's state.
     *
     * @var array
     */
    public $state = [];

    /**
     * Create a new team.
     */
    public function createTeam(): void
    {
        $this->resetErrorBag();
    }

    /**
     * Get the current user of the application.
     */
    public function getUserProperty(): mixed
    {
        return Auth::user();
    }

    /**
     * Render the component.
     */
    public function render(): \Illuminate\View\View
    {
        return view('teams.create-team-form');
    }
}
