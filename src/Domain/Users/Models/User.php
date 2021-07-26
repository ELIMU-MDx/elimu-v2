<?php

namespace Domain\Users\Models;

use Domain\Study\Models\Membership;
use Domain\Study\Models\Study;
use Domain\Users\QueryBuilders\UserQueryBuilder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

/**
 * Domain\Users\Models\User
 *
 * @property int $id
 * @property int|null $study_id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $two_factor_secret
 * @property string|null $two_factor_recovery_codes
 * @property string|null $remember_token
 * @property string|null $profile_photo_path
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Study|null $currentStudy
 * @property-read string $profile_photo_url
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[]
 *     $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection|Study[] $studies
 * @property-read int|null $studies_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Sanctum\PersonalAccessToken[] $tokens
 * @property-read int|null $tokens_count
 * @method static UserQueryBuilder|User isMemberOfAStudy(int $userId)
 * @method static UserQueryBuilder|User newModelQuery()
 * @method static UserQueryBuilder|User newQuery()
 * @method static UserQueryBuilder|User query()
 * @method static UserQueryBuilder|User whereCreatedAt($value)
 * @method static UserQueryBuilder|User whereEmail($value)
 * @method static UserQueryBuilder|User whereEmailVerifiedAt($value)
 * @method static UserQueryBuilder|User whereId($value)
 * @method static UserQueryBuilder|User whereName($value)
 * @method static UserQueryBuilder|User wherePassword($value)
 * @method static UserQueryBuilder|User whereProfilePhotoPath($value)
 * @method static UserQueryBuilder|User whereRememberToken($value)
 * @method static UserQueryBuilder|User whereStudyId($value)
 * @method static UserQueryBuilder|User whereTwoFactorRecoveryCodes($value)
 * @method static UserQueryBuilder|User whereTwoFactorSecret($value)
 * @method static UserQueryBuilder|User whereUpdatedAt($value)
 * @mixin \Eloquent
 */
final class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
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

    public function newEloquentBuilder($query): UserQueryBuilder
    {
        return new UserQueryBuilder($query);
    }

    public function isCurrentStudy(Study $study): bool
    {
        return $study->id === $this->study_id;
    }
}
