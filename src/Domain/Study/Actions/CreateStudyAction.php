<?php

declare(strict_types=1);

namespace Domain\Study\Actions;

use Domain\Study\DataTransferObject\CreateStudyParameter;
use Domain\Study\Enums\Role;
use Domain\Study\Models\Study;
use Domain\Study\Roles\Owner;
use Domain\Users\Models\User;
use Illuminate\Database\Connection;

final class CreateStudyAction
{
    /** @var \Illuminate\Database\Connection */
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function execute(User $user, CreateStudyParameter $parameter): Study
    {
        return $this->connection->transaction(function () use ($user, $parameter) {
            $study = Study::create([
                'name' => $parameter->name,
                'identifier' => $parameter->identifier,
            ]);
            $study->users()->attach($user->id, ['role' => new Owner()]);
            $user->study_id = $study->id;
            $user->save();

            return $study;
        });
    }
}
