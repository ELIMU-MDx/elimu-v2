<?php

namespace App\Livewire;

use Illuminate\Contracts\View\View;
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
     *
     * @return void
     */
    public function mount(mixed $team)
    {
        $this->team = $team;

        $this->state = $team->withoutRelations()->toArray();
    }

    /**
     * Update the team's name.
     *
     * @return void
     */
    public function updateTeamName(UpdatesTeamNames $updater)
    {
        $this->resetErrorBag();

        $updater->update($this->user, $this->team, $this->state);

        $this->dispatch('saved');

        $this->dispatch('refresh-navigation-menu');
    }

    /**
     * Get the current user of the application.
     *
     * @return mixed
     */
    public function getUserProperty()
    {
        return Auth::user();
    }

    /**
     * Render the component.
     *
     * @return View
     */
    public function render()
    {
        return view('teams.update-team-name-form');
    }
}
