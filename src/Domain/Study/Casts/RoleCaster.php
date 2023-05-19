<?php

declare(strict_types=1);

namespace Domain\Study\Casts;

use Domain\Study\Roles\Role;
use Domain\Study\Roles\RoleFactory;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

final class RoleCaster implements CastsAttributes
{
    public function get(Model $model, string $key, mixed $value, array $attributes): Role
    {
        return RoleFactory::get($value);
    }

    /**
     * @param Model $model
     * @param  string  $key
     * @param Role $value
     * @param  array  $attributes
     * @return string
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): string
    {
        return $value->identifier();
    }
}
