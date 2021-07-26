<?php

declare(strict_types=1);

namespace App\View\Livewire;

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
     */
    public function mount(mixed $team): void
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
     */
    public function render(): \Illuminate\View\View
    {
        return view('teams.delete-team-form');
    }
}
