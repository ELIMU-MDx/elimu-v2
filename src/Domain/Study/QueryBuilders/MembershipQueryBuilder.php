<?php

declare(strict_types=1);

namespace Domain\Study\QueryBuilders;

use Domain\Study\Roles\Role;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;

/**
 * @template TModelClass of \Illuminate\Database\Eloquent\Model
 *
 * @extends Builder<TModelClass>
 */
final class MembershipQueryBuilder extends Builder
{
    public function isMemberOfStudy(int $userId, int $studyId): bool
    {
        return $this->where('user_id', $userId)
            ->where('study_id', $studyId)
            ->exists();
    }

    public function isRoleOfStudy(int $userId, int $studyId, array|Role $roles): bool
    {
        $roles = collect(Arr::wrap($roles))->map->identifier();

        return $this->where('user_id', $userId)
            ->where('study_id', $studyId)
            ->whereIn('role', $roles)
            ->exists();
    }
}
