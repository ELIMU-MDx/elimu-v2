<?php

declare(strict_types=1);

namespace App\View\Livewire;

use Auth;
use Domain\Study\Actions\AddMemberAction;
use Domain\Study\Actions\RemoveTeamMemberAction;
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

    public function mount(RoleFactory $roleFactory, Study $study): void
    {
        $this->roles = Collection::make($roleFactory->toArray());
        $this->study = $study;
    }

    public function addMember(AddMemberAction $addMemberAction): void
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

        $addMemberAction->execute(
            email: $this->addMemberForm['email'],
            role: $this->addMemberForm['role'],
            inviterId: Auth::user()->id,
            studyId: $this->study->id
        );

        $this->addMemberForm = [
            'email' => '',
            'role' => null,
        ];

        $this->study = $this->study->fresh();

        $this->emit('saved');
    }

    /**
     * Confirm that the given team member should be removed.
     */
    public function confirmingMemberRemoval(int $userId): void
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

    public function cancelTeamInvitation(int $invitationId): void
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