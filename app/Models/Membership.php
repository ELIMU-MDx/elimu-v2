<?php

declare(strict_types=1);

namespace App\Models;

use Domain\Study\Casts\RoleCaster;
use Domain\Study\QueryBuilders\MembershipQueryBuilder;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Query\Builder;

final class Membership extends Pivot
{
    protected $table = 'study_user';

    protected $casts = [
        'role' => RoleCaster::class,
    ];

    /**
     * @param  Builder  $query
     * @return MembershipQueryBuilder<Membership>
     */
    public function newEloquentBuilder($query): MembershipQueryBuilder
    {
        return new MembershipQueryBuilder($query);
    }
}
