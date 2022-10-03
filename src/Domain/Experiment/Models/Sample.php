<?php

declare(strict_types=1);

namespace Domain\Experiment\Models;

use Domain\Experiment\QueryBuilders\ExperimentQueryBuilder;
use Domain\Experiment\QueryBuilders\SampleQueryBuilder;
use Domain\Results\Models\Result;
use Domain\Study\Models\Study;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Query\Builder;
use Kirschbaum\PowerJoins\PowerJoins;

final class Sample extends Model
{
    use PowerJoins;

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

    /**
     * @param Builder $query
     *
     * @return SampleQueryBuilder<Sample>
     */
    public function newEloquentBuilder($query): SampleQueryBuilder
    {
        return new SampleQueryBuilder($query);
    }
}
