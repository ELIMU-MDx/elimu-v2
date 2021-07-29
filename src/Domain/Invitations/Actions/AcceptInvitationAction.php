<?php

declare(strict_types=1);

namespace Domain\Invitations\Actions;

use BadMethodCallException;
use Domain\Invitations\Models\Invitation;
use Illuminate\Database\Connection;

final class AcceptInvitationAction
{
    public function __construct(private Connection $connection)
    {
    }

    public function execute(Invitation $invitation): void
    {
        $invitation->fresh(['receiver']);

        if ($invitation->receiver === null) {
            throw new BadMethodCallException('There is no user for the receiver email address '.$invitation->email);
        }

        $receiver = $invitation->receiver;

        $invitation->delete();

        $receiver->studies()->attach($invitation->study_id, ['role' => $invitation->role]);
        $receiver->study_id = $invitation->study_id;

        $this->connection->transaction(function () use ($receiver, $invitation) {
            $invitation->delete();
            $receiver->save();
        });
    }
}
