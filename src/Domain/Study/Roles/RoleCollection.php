<?php

declare(strict_types=1);

namespace Domain\Study\Roles;

use Illuminate\Support\Collection;

final class RoleCollection extends Collection
{
    public function identifiers(): Collection
    {
        return $this->map(function (Role $role) {
            return $role->identifier();
        });
    }
}
