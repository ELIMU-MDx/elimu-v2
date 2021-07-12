<?php

namespace App\View\Livewire;

use Auth;
use Domain\Study\Actions\RemoveStudyInvitationAction;
use Domain\Study\Actions\RemoveTeamMemberAction;
use Domain\Study\Enums\InvitationStatus;
use Domain\Study\Models\Invitation;
use Domain\Study\Models\Study;
use Domain\Study\Roles\RoleFactory;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Livewire\Component;

class StudyMemberManager extends Component
{
    /**
     * The team instance.
     *
     * @var Study
     */
    public $study;

    /**
     * @var Collection
     */
    public $roles;

    /**
     * The "add team member" form state.
     *
     * @var array
     */
    public $addMemberForm = [
        'email' => '',
        'role' => null,
    ];

    /** @var bool */
    public $confirmingMemberRemoval;

    /** @var int */
    public $teamMemberIdBeingRemoved;

    public function mount(RoleFactory $roleFactory, $study): void
    {
        $this->roles = Collection::make($roleFactory->toArray());
        $this->study = $study;
    }

    public function addMember()
    {
        $this->resetErrorBag();

        $this->validate([
            'addMemberForm.email' => [
                'required', 'email', Rule::unique('invitations', 'email')->where('study_id', $this->study->id),
            ],
            'addMemberForm.role' => ['required', 'string', Rule::in($this->roles->pluck('identifier'))],
        ], [
            'addMemberForm.email.unique' => 'This user has already been invited to the team.',
        ], [
            'addMemberForm.email' => 'email',
            'addMemberForm.role' => 'role',
        ]);
        // TODO: send email, check if user has not rejected invitation already

        $this->study->invitations()->save(new Invitation([
            'email' => $this->addMemberForm['email'],
            'role' => $this->addMemberForm['role'],
            'user_id' => Auth::user()->id,
            'status' => InvitationStatus::PENDING(),
        ]));

        $this->addMemberForm = [
            'email' => '',
            'role' => null,
        ];

        $this->study = $this->study->fresh();

        $this->emit('saved');
    }

    /**
     * Confirm that the given team member should be removed.
     *
     * @param  int  $userId
     * @return void
     */
    public function confirmingMemberRemoval($userId): void
    {
        $this->confirmingMemberRemoval = true;

        $this->teamMemberIdBeingRemoved = $userId;
    }

    public function removeMember(RemoveTeamMemberAction $removeTeamMemberAction): void
    {
        $removeTeamMemberAction->execute($this->study, $this->teamMemberIdBeingRemoved, Auth::user());

        $this->confirmingMemberRemoval = false;
        $this->teamMemberIdBeingRemoved = null;

        $this->study = $this->study->fresh();
    }

    /**
     * @param  int  $invitationId
     * @return void
     */
    public function cancelTeamInvitation($invitationId): void
    {
        if ($invitationId) {
            Invitation::whereKey($invitationId)->where('study_id', $this->study->id)->delete();
        }

        $this->study = $this->study->fresh();
    }

    public function render(): View
    {
        return view('studies.study-member-manager');
    }
}
