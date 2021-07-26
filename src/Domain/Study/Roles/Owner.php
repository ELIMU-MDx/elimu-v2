<?php

declare(strict_types=1);

namespace Domain\Study\Roles;

final class Owner implements Role
{
    public function identifier(): string
    {
        return 'owner';
    }

    public function description(): string
    {
        return 'An owner has the same rights as a lab manager but only another owner
                            can remove him from the team';
    }

    public function title(): string
    {
        return 'Owner';
    }
}
