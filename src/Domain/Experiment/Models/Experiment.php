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

/**
 * Domain\Experiment\Models\Experiment
 *
 * @property int $id
 * @property int $study_id
 * @property int $assay_id
 * @property int|null $user_id
 * @property string $name
 * @property string|null $rdml_path
 * @property string|null $eln
 * @property mixed|null $experiment_date
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @property-read Assay $assay
 * @property-read \Domain\Experiment\Collections\MeasurementCollection|array<\Domain\Experiment\Models\Measurement> $controls
 * @property-read int|null $controls_count
 * @property-read User $creator
 * @property-read \Domain\Experiment\Collections\MeasurementCollection|array<\Domain\Experiment\Models\Measurement> $measurements
 * @property-read int|null $measurements_count
 * @property-read Study $study
 *
 * @method static ExperimentQueryBuilder|Experiment newModelQuery()
 * @method static ExperimentQueryBuilder|Experiment newQuery()
 * @method static ExperimentQueryBuilder|Experiment query()
 * @method static ExperimentQueryBuilder|Experiment whereAssayId($value)
 * @method static ExperimentQueryBuilder|Experiment whereCreatedAt($value)
 * @method static ExperimentQueryBuilder|Experiment whereEln($value)
 * @method static ExperimentQueryBuilder|Experiment whereExperimentDate($value)
 * @method static ExperimentQueryBuilder|Experiment whereId($value)
 * @method static ExperimentQueryBuilder|Experiment whereName($value)
 * @method static ExperimentQueryBuilder|Experiment whereRdmlPath($value)
 * @method static ExperimentQueryBuilder|Experiment whereStudyId($value)
 * @method static ExperimentQueryBuilder|Experiment whereUpdatedAt($value)
 * @method static ExperimentQueryBuilder|Experiment whereUserId($value)
 * @method static ExperimentQueryBuilder|Experiment withSamplesCount()
 *
 * @mixin \Eloquent
 */
final class Experiment extends Model
{
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
}
