<?php

declare(strict_types=1);

namespace Domain\Study\Casts;

use Domain\Study\Roles\Role;
use Domain\Study\Roles\RoleFactory;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

final class RoleCaster implements CastsAttributes
{
    public function get($model, string $key, $value, array $attributes): Role
    {
        return RoleFactory::get($value);
    }

    /**
     * @param  array  $attributes
     */
    public function set(
        \Illuminate\Database\Eloquent\Model $model,
        string $key,
        \Domain\Study\Roles\Role $value,
        array $attributes
    ): string {
        return $value->identifier();
    }
}
