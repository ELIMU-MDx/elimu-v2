<?php

declare(strict_types=1);

namespace Domain\Study\Actions;

use Domain\Invitations\Models\Invitation;
use Domain\Study\DataTransferObject\StudyMemberParameters;
use Domain\Users\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Connection;

final class CreateStudyMemberAction
{
    public function __construct(private Connection $connection)
    {
    }

    public function execute(Invitation $invitation, StudyMemberParameters $parameters): User
    {
        return $this->connection->transaction(function () use ($invitation, $parameters) {
            $user = User::create([
                'name' => $parameters->name,
                'email' => $parameters->email,
                'password' => Hash::make($parameters->password),
                'study_id' => $invitation->study_id,
            ]);
            $user->studies()->attach($invitation->study_id, ['role' => $invitation->role]);

            return $user;
        });
    }
}
