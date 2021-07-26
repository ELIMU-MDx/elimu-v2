<?php

declare(strict_types=1);

namespace App\View\Livewire;

use Illuminate\Support\Facades\Auth;
use Laravel\Jetstream\Contracts\UpdatesTeamNames;
use Livewire\Component;

class UpdateTeamNameForm extends Component
{
    /**
     * The team instance.
     *
     * @var mixed
     */
    public $team;

    /**
     * The component's state.
     *
     * @var array
     */
    public $state = [];

    /**
     * Mount the component.
     */
    public function mount(mixed $team): void
    {
        $this->team = $team;

        $this->state = $team->withoutRelations()->toArray();
    }

    /**
     * Update the team's name.
     */
    public function updateTeamName(UpdatesTeamNames $updater): void
    {
        $this->resetErrorBag();

        $updater->update($this->user, $this->team, $this->state);

        $this->emit('saved');

        $this->emit('refresh-navigation-menu');
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
        return view('teams.update-team-name-form');
    }
}
