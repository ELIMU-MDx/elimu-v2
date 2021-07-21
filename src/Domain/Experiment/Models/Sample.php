<?php

declare(strict_types=1);

namespace Domain\Experiment\Models;

use Domain\Evaluation\Models\Result;
use Domain\Experiment\QueryBuilders\SampleQueryBuilder;
use Domain\Study\Models\Study;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class Sample extends Model
{
    public function study(): BelongsTo
    {
        return $this->belongsTo(Study::class);
    }

    public function measurements(): HasMany
    {
        return $this->hasMany(Measurement::class);
    }

    public function results(): HasMany
    {
        return $this->hasMany(Result::class)->orderBy('results.target');
    }

    public function newEloquentBuilder($query): SampleQueryBuilder
    {
        return new SampleQueryBuilder($query);
    }
}
