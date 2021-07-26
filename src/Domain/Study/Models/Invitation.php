<?php

declare(strict_types=1);

namespace Domain\Study\Models;

use Domain\Study\Casts\RoleCaster;
use Domain\Study\Enums\InvitationStatus;
use Domain\Users\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Domain\Study\Models\Invitation
 *
 * @property int $id
 * @property int $study_id
 * @property int|null $user_id
 * @property string $email
 * @property \Domain\Study\Roles\Role $role
 * @property InvitationStatus $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read User $receiver
 * @property-read \Domain\Study\Models\Study $study
 * @property-read User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|Invitation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Invitation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Invitation query()
 * @method static \Illuminate\Database\Eloquent\Builder|Invitation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invitation whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invitation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invitation whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invitation whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invitation whereStudyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invitation whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invitation whereUserId($value)
 * @mixin \Eloquent
 */
final class Invitation extends Model
{
    protected $casts = [
        'status' => InvitationStatus::class,
        'role' => RoleCaster::class,
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function receiver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'email', 'email');
    }

    public function study(): BelongsTo
    {
        return $this->belongsTo(Study::class);
    }
}
