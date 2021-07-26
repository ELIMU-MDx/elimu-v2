<?php

declare(strict_types=1);

namespace Domain\Study\Actions;

use Domain\Study\Enums\InvitationStatus;
use Domain\Study\Mailable\NewUserInvitationMail;
use Domain\Study\Models\Invitation;
use Domain\Study\Roles\RoleFactory;
use Illuminate\Contracts\Mail\Factory;

final class AddMemberAction
{
    public function __construct(private Factory $mail)
    {
    }

    public function execute(string $email, string $role, string $inviterId, string $studyId): void
    {
        $invitation = Invitation::create([
            'email' => $email,
            'role' => RoleFactory::get($role),
            'study_id' => $studyId,
            'user_id' => $inviterId,
            'status' => InvitationStatus::PENDING(),
        ]);

        $this->mail->send(new NewUserInvitationMail($invitation));
    }
}
