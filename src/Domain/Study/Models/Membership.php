<?php

declare(strict_types=1);

namespace Domain\Study\Models;

use Domain\Study\Casts\RoleCaster;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * Domain\Study\Models\Membership
 *
 * @property int $id
 * @property int $user_id
 * @property int $study_id
 * @property \Domain\Study\Roles\Role $role
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Membership newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Membership newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Membership query()
 * @method static \Illuminate\Database\Eloquent\Builder|Membership whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Membership whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Membership whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Membership whereStudyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Membership whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Membership whereUserId($value)
 *
 * @mixin \Eloquent
 */
final class Membership extends Pivot
{
    protected $table = 'study_user';

    protected $casts = [
        'role' => RoleCaster::class,
    ];
}
