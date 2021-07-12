<?php

declare(strict_types=1);

namespace Domain\Study\Models;

use Domain\Study\Casts\RoleCaster;
use Illuminate\Database\Eloquent\Relations\Pivot;

final class Membership extends Pivot
{
    protected $table = 'study_user';

    protected $casts = [
        'role' => RoleCaster::class,
    ];
}
