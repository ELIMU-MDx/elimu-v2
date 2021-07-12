<?php

declare(strict_types=1);

namespace Domain\Study\Roles;

interface Role
{
    public function identifier(): string;

    public function description(): string;

    public function title(): string;
}
