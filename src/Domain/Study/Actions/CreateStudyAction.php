<?php

declare(strict_types=1);

namespace Domain\Study\Actions;

use App\Models\Study;
use App\Models\User;
use Domain\Study\DataTransferObject\CreateStudyParameter;
use Domain\Study\Roles\Owner;
use Illuminate\Database\Connection;

final class CreateStudyAction
{
    public function __construct(private readonly Connection $connection)
    {
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
