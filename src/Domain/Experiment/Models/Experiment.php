<?php

declare(strict_types=1);

namespace Domain\Experiment\Models;

use Domain\Assay\Models\Assay;
use Domain\Experiment\QueryBuilders\ExperimentQueryBuilder;
use Domain\Rdml\Enums\MeasurementType;
use Domain\Study\Models\Study;
use Domain\Users\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

final class Experiment extends Model
{
    use LogsActivity;

    protected $casts = [
        'experiment_date' => 'date:Y-m-d',
    ];

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

    public function controls(): HasMany
    {
        return $this->hasMany(Measurement::class)->where('type', '<>', MeasurementType::SAMPLE());
    }

    public function newEloquentBuilder($query): ExperimentQueryBuilder
    {
        return new ExperimentQueryBuilder($query);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'eln', 'experiment_date']);
    }
}
