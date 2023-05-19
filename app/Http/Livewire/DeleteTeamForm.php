<?php

namespace App\Http\Livewire;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Laravel\Jetstream\Actions\ValidateTeamDeletion;
use Laravel\Jetstream\Contracts\DeletesTeams;
use Laravel\Jetstream\RedirectsActions;
use Livewire\Component;

class DeleteTeamForm extends Component
{
    use RedirectsActions;

    /**
     * The team instance.
     *
     * @var mixed
     */
    public $team;

    /**
     * Indicates if team deletion is being confirmed.
     *
     * @var bool
     */
    public $confirmingTeamDeletion = false;

    /**
     * Mount the component.
     *
     * @return void
     */
    public function mount(mixed $team)
    {
        $this->team = $team;
    }

    /**
     * Delete the team.
     *
     * @return void
     */
    public function deleteTeam(ValidateTeamDeletion $validator, DeletesTeams $deleter)
    {
        $validator->validate(Auth::user(), $this->team);

        $deleter->delete($this->team);

        return $this->redirectPath($deleter);
    }

    /**
     * Render the component.
     *
     * @return View
     */
    public function render()
    {
        return view('teams.delete-team-form');
    }
}
