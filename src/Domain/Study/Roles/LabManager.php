<?php

declare(strict_types=1);

namespace Domain\Study\Roles;

final class LabManager implements Role
{
    public function identifier(): string
    {
        return 'lab-manager';
    }

    public function description(): string
    {
        return 'A lab manager can invite / remove team members and has full access over all experiment and monitoring data';
    }

    public function title(): string
    {
        return 'Lab manager';
    }
}
