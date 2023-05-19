<?php

declare(strict_types=1);

namespace Domain\Users\QueryBuilders;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

/**
 * @template TModelClass of \Illuminate\Database\Eloquent\Model
 *
 * @extends Builder<TModelClass>
 */
final class UserQueryBuilder extends Builder
{
    public function isMemberOfAnyStudy(int $userId): bool
    {
        return $this->select(DB::raw(1))
            ->from('study_user')
            ->where('user_id', $userId)
            ->exists();
    }
}
