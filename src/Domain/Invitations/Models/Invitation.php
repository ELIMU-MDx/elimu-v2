<?php

declare(strict_types=1);

namespace Domain\Invitations\Models;

use Domain\Study\Casts\RoleCaster;
use Domain\Study\Enums\InvitationStatus;
use Domain\Study\Models\Study;
use Domain\Users\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
