<?php

declare(strict_types=1);

namespace Domain\Evaluation\Models;

use Domain\Assay\Models\Assay;
use Domain\Assay\Models\AssayParameter;
use Domain\Experiment\Models\Measurement;
use Domain\Experiment\Models\Sample;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

final class Result extends Model
{
    public function measurements(): HasMany
    {
        return $this->hasMany(Measurement::class);
    }

    public function sample(): BelongsTo
    {
        return $this->belongsTo(Sample::class);
    }

    public function resultErrors(): HasMany
    {
        return $this->hasMany(ResultError::class);
    }

    public function parameters(): HasManyThrough
    {
        return $this->hasManyThrough(AssayParameter::class, Assay::class);
    }

    public function assay(): BelongsTo
    {
        return $this->belongsTo(Assay::class);
    }
}
