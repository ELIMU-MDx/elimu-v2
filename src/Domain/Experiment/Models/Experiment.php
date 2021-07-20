<?php

declare(strict_types=1);

namespace Domain\Experiment\Models;

use Domain\Assay\Models\Assay;
use Domain\Study\Models\Study;
use Domain\Users\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

final class Experiment extends Model implements HasMedia
{
    use InteractsWithMedia;

    public function study(): BelongsTo
    {
        return $this->belongsTo(Study::class);
    }

    public function measurements(): HasMany
    {
        return $this->hasMany(Measurement::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function assay(): BelongsTo
    {
        return $this->belongsTo(Assay::class);
    }
}
