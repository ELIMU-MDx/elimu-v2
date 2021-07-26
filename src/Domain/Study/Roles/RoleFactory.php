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

        return self::$roles->first(function (Role $role) use ($identifier) {
            return $role->identifier() === $identifier;
        }) ?? throw new RuntimeException("Could not find role for identifier '{$identifier}'");
    }

    private static function initialize(): void
    {
        if (self::$roles) {
            return;
        }

        self::$roles = RoleCollection::make([
            new Owner(),
            new LabManager(),
            new Scientist(),
            new Monitor(),
        ]);
    }

    public static function all(): RoleCollection
    {
        self::initialize();

        return self::$roles;
    }

    public static function toArray(): array
    {
        self::initialize();

        return self::$roles->map(function (Role $role) {
            return [
                'identifier' => $role->identifier(),
                'description' => $role->description(),
                'title' => $role->title(),
            ];
        })->toArray();
    }
}
