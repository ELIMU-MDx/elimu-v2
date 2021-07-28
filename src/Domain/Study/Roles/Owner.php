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
        return 'An owner can modify study settings, add or remove members and everything a scientist can.';
    }

    public function title(): string
    {
        return 'Owner';
    }
}
