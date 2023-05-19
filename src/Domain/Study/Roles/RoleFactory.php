<?php

declare(strict_types=1);

namespace Domain\Study\Roles;

use RuntimeException;

final class RoleFactory
{
    private static ?RoleCollection $roles = null;

    public static function get(string $identifier): Role
    {
        self::initialize();

        return self::$roles->first(fn(Role $role) => $role->identifier() === $identifier) ?? throw new RuntimeException("Could not find role for identifier '{$identifier}'");
    }

    public static function all(): RoleCollection
    {
        self::initialize();

        return self::$roles;
    }

    public static function toArray(): array
    {
        self::initialize();

        return self::$roles->map(fn(Role $role) => [
            'identifier' => $role->identifier(),
            'description' => $role->description(),
            'title' => $role->title(),
        ])->toArray();
    }

    private static function initialize(): void
    {
        if (self::$roles) {
            return;
        }

        self::$roles = RoleCollection::make([
            new Owner(),
            new Scientist(),
            new Monitor(),
        ]);
    }
}
