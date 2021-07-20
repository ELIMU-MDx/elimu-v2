<?php

declare(strict_types=1);

namespace Domain\Evaluation\Models;

use Domain\Assay\Models\Assay;
use Domain\Experiment\Models\Measurement;
use Domain\Experiment\Models\Sample;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

    public function assay(): BelongsTo
    {
        return $this->belongsTo(Assay::class);
    }
}
