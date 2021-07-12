<?php

declare(strict_types=1);

namespace Domain\Study\Roles;

final class Scientist implements Role
{

    public function identifier(): string
    {
        return 'scientist';
    }

    public function description(): string
    {
        return 'A scientist has full access over all experiment data';
    }

    public function title(): string
    {
        return 'Scientist';
    }
}
