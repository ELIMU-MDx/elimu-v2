<?php

declare(strict_types=1);

namespace Domain\Study\Models;

use Domain\Experiment\Models\Experiment;
use Domain\Experiment\Models\Sample;
use Domain\Users\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Domain\Study\Models\Study
 *
 * @property int $id
 * @property string $identifier
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|Experiment[] $experiments
 * @property-read int|null $experiments_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Domain\Study\Models\Invitation[] $invitations
 * @property-read int|null $invitations_count
 * @property-read \Illuminate\Database\Eloquent\Collection|Sample[] $samples
 * @property-read int|null $samples_count
 * @property-read \Illuminate\Database\Eloquent\Collection|User[] $users
 * @property-read int|null $users_count
 * @method static \Illuminate\Database\Eloquent\Builder|Study newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Study newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Study query()
 * @method static \Illuminate\Database\Eloquent\Builder|Study whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Study whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Study whereIdentifier($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Study whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Study whereUpdatedAt($value)
 * @mixin \Eloquent
 */
final class Study extends Model
{
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)
            ->using(Membership::class)
            ->withPivot('role')
            ->withTimestamps()
            ->as('membership');
    }

    public function invitations(): HasMany
    {
        return $this->hasMany(Invitation::class);
    }

    public function experiments(): HasMany
    {
        return $this->hasMany(Experiment::class);
    }

    public function samples(): HasMany
    {
        return $this->hasMany(Sample::class);
    }
}
