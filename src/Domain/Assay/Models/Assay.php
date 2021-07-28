<?php

declare(strict_types=1);

namespace Domain\Assay\Models;

use Domain\Experiment\Models\Experiment;
use Domain\Experiment\Models\Measurement;
use Domain\Results\Models\Result;
use Domain\Study\Models\Study;
use Domain\Users\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

final class Assay extends Model
{
    use LogsActivity;

    public function experiments(): HasMany
    {
        return $this->hasMany(Experiment::class);
    }

    public function parameters(): HasMany
    {
        return $this->hasMany(AssayParameter::class, 'assay_id', 'id');
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

    public function measurements(): HasManyThrough
    {
        return $this->hasManyThrough(Measurement::class, Experiment::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logOnly(['name']);
    }
}
