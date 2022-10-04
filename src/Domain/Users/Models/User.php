<?php

namespace Domain\Users\Models;

use Domain\Study\Models\Membership;
use Domain\Study\Models\Study;
use Domain\Study\QueryBuilders\MembershipQueryBuilder;
use Domain\Users\QueryBuilders\UserQueryBuilder;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

final class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $appends = [
        'profile_photo_url',
    ];

    public function studies(): BelongsToMany
    {
        return $this->belongsToMany(Study::class)
            ->using(Membership::class)
            ->withPivot('role')
            ->withTimestamps()
            ->as('membership');
    }

    public function currentStudy(): BelongsTo
    {
        return $this->belongsTo(Study::class, 'study_id');
    }

    public function isCurrentStudy(Study $study): bool
    {
        return $study->id === $this->study_id;
    }

    /**
     * @param  \Illuminate\Database\Query\Builder  $query
     *
     * @return UserQueryBuilder<User>
     */
    public function newEloquentBuilder($query): Builder
    {
        return new UserQueryBuilder($query);
    }
}
