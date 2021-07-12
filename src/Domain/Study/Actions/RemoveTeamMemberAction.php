<?php

declare(strict_types=1);

namespace Domain\Study\Actions;

use Domain\Study\Models\Study;
use Domain\Users\Models\User;

final class RemoveTeamMemberAction
{
    public function execute(Study $study, int $userId, User $auth)
    {
        // check user has permission to remove a member
        // check user is not last owner

        $study->users()->detach($userId);
    }
}
