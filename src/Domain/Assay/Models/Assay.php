<?php

declare(strict_types=1);

namespace Domain\Assay\Models;

use Domain\Evaluation\Models\Result;
use Domain\Study\Models\Study;
use Domain\Users\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class Assay extends Model
{
    public function parameters(): HasMany
    {
        return $this->hasMany(AssayParameter::class);
    }

    public function study(): BelongsTo
    {
        return $this->belongsTo(Study::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id')->withDefault([
            'name' => 'Unknown',
            'id' => -1,
        ]);
    }

    public function results(): HasMany
    {
        return $this->hasMany(Result::class);
    }
}
