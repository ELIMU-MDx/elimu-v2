<?php

declare(strict_types=1);

namespace Domain\Study\Models;

use Domain\Experiment\Models\Experiment;
use Domain\Experiment\Models\Sample;
use Domain\Invitations\Models\Invitation;
use Domain\Study\Enums\InvitationStatus;
use Domain\Users\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

    public function pendingInvitations(): HasMany
    {
        return $this->hasMany(Invitation::class)->where('status', InvitationStatus::PENDING());
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
