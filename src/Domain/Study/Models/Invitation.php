<?php

declare(strict_types=1);

namespace Domain\Study\Models;

use Domain\Study\Enums\InvitationStatus;
use Domain\Users\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class Invitation extends Model
{
    protected $casts = [
        'status' => InvitationStatus::class,
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function study(): BelongsTo
    {
        return $this->belongsTo(Study::class);
    }
}
