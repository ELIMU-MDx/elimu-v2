<?php

declare(strict_types=1);

namespace Domain\Study\Roles;

final class Monitor implements Role
{
    public function identifier(): string
    {
        return 'monitor';
    }

    public function description(): string
    {
        return 'A monitor has read only rights over all data. He cannot edit or delete any data.';
    }

    public function title(): string
    {
        return 'Monitor';
    }
}
