<?php

declare(strict_types=1);

namespace Domain\Assay\Models;

use Domain\Experiment\Models\Experiment;
use Domain\Results\Models\Result;
use Domain\Study\Models\Study;
use Domain\Users\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Domain\Assay\Models\Assay
 *
 * @property int $id
 * @property int|null $study_id
 * @property string $name
 * @property string|null $sample_type
 * @property int|null $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read User|null $creator
 * @property-read \Illuminate\Database\Eloquent\Collection|Experiment[] $experiments
 * @property-read int|null $experiments_count
 * @property-read \Domain\Assay\Collections\ParameterCollection|\Domain\Assay\Models\AssayParameter[] $parameters
 * @property-read int|null $parameters_count
 * @property-read \Illuminate\Database\Eloquent\Collection|Result[] $results
 * @property-read int|null $results_count
 * @property-read Study|null $study
 * @method static \Illuminate\Database\Eloquent\Builder|Assay newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Assay newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Assay query()
 * @method static \Illuminate\Database\Eloquent\Builder|Assay whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Assay whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Assay whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Assay whereSampleType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Assay whereStudyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Assay whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Assay whereUserId($value)
 * @mixin \Eloquent
 */
final class Assay extends Model
{
    public function experiments(): HasMany
    {
        return $this->hasMany(Experiment::class);
    }

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
