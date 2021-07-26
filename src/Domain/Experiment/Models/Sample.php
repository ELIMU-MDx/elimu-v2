<?php

declare(strict_types=1);

namespace Domain\Experiment\Models;

use Domain\Experiment\QueryBuilders\SampleQueryBuilder;
use Domain\Results\Models\Result;
use Domain\Study\Models\Study;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Kirschbaum\PowerJoins\PowerJoins;

/**
 * Domain\Experiment\Models\Sample
 *
 * @property int $id
 * @property int $study_id
 * @property string $identifier
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Domain\Experiment\Collections\MeasurementCollection|\Domain\Experiment\Models\Measurement[]
 *     $measurements
 * @property-read int|null $measurements_count
 * @property-read \Illuminate\Database\Eloquent\Collection|Result[] $results
 * @property-read int|null $results_count
 * @property-read Study $study
 * @method static SampleQueryBuilder|Sample countAll(int $assayId, int $studyId)
 * @method static SampleQueryBuilder|Sample filterByResult(string $filter)
 * @method static SampleQueryBuilder|Sample hasNestedUsingJoins($relations, $operator = '>=', $count = 1, $boolean =
 *     'and', ?\Closure $callback = null)
 * @method static SampleQueryBuilder|Sample joinNestedRelationship(string $relationships, $callback = null, $joinType =
 *     'join', $useAlias = false, bool $disableExtraConditions = false)
 * @method static SampleQueryBuilder|Sample joinRelation($relationName, $callback = null, $joinType = 'join', $useAlias
 *     = false, bool $disableExtraConditions = false)
 * @method static SampleQueryBuilder|Sample joinRelationship($relationName, $callback = null, $joinType = 'join',
 *     $useAlias = false, bool $disableExtraConditions = false)
 * @method static SampleQueryBuilder|Sample joinRelationshipUsingAlias($relationName, $callback = null, bool
 *     $disableExtraConditions = false)
 * @method static SampleQueryBuilder|Sample leftJoinRelation($relation, $callback = null, $useAlias = false, bool
 *     $disableExtraConditions = false)
 * @method static SampleQueryBuilder|Sample leftJoinRelationship($relation, $callback = null, $useAlias = false, bool
 *     $disableExtraConditions = false)
 * @method static SampleQueryBuilder|Sample leftJoinRelationshipUsingAlias($relationName, $callback = null, bool
 *     $disableExtraConditions = false)
 * @method static SampleQueryBuilder|Sample listAll(int $assayId, int $studyId)
 * @method static SampleQueryBuilder|Sample newModelQuery()
 * @method static SampleQueryBuilder|Sample newQuery()
 * @method static SampleQueryBuilder|Sample orderByLeftPowerJoins($sort, $direction = 'asc')
 * @method static SampleQueryBuilder|Sample orderByLeftPowerJoinsAvg($sort, $direction = 'asc')
 * @method static SampleQueryBuilder|Sample orderByLeftPowerJoinsCount($sort, $direction = 'asc')
 * @method static SampleQueryBuilder|Sample orderByLeftPowerJoinsMax($sort, $direction = 'asc')
 * @method static SampleQueryBuilder|Sample orderByLeftPowerJoinsMin($sort, $direction = 'asc')
 * @method static SampleQueryBuilder|Sample orderByLeftPowerJoinsSum($sort, $direction = 'asc')
 * @method static SampleQueryBuilder|Sample orderByPowerJoins($sort, $direction = 'asc', $aggregation = null, $joinType
 *     = 'join')
 * @method static SampleQueryBuilder|Sample orderByPowerJoinsAvg($sort, $direction = 'asc')
 * @method static SampleQueryBuilder|Sample orderByPowerJoinsCount($sort, $direction = 'asc')
 * @method static SampleQueryBuilder|Sample orderByPowerJoinsMax($sort, $direction = 'asc')
 * @method static SampleQueryBuilder|Sample orderByPowerJoinsMin($sort, $direction = 'asc')
 * @method static SampleQueryBuilder|Sample orderByPowerJoinsSum($sort, $direction = 'asc')
 * @method static SampleQueryBuilder|Sample powerJoinDoesntHave($relation, $boolean = 'and', ?\Closure $callback =
 *     null)
 * @method static SampleQueryBuilder|Sample powerJoinHas($relation, $operator = '>=', $count = 1, $boolean = 'and',
 *     ?\Closure $callback = null)
 * @method static SampleQueryBuilder|Sample powerJoinWhereHas($relation, ?\Closure $callback = null, $operator = '>=',
 *     $count = 1)
 * @method static SampleQueryBuilder|Sample query()
 * @method static SampleQueryBuilder|Sample rightJoinRelation($relation, $callback = null, $useAlias = false, bool
 *     $disableExtraConditions = false)
 * @method static SampleQueryBuilder|Sample rightJoinRelationship($relation, $callback = null, $useAlias = false, bool
 *     $disableExtraConditions = false)
 * @method static SampleQueryBuilder|Sample rightJoinRelationshipUsingAlias($relationName, $callback = null, bool
 *     $disableExtraConditions = false)
 * @method static SampleQueryBuilder|Sample searchBySampleIdentifier(string $search)
 * @method static SampleQueryBuilder|Sample whereCreatedAt($value)
 * @method static SampleQueryBuilder|Sample whereId($value)
 * @method static SampleQueryBuilder|Sample whereIdentifier($value)
 * @method static SampleQueryBuilder|Sample whereStudyId($value)
 * @method static SampleQueryBuilder|Sample whereUpdatedAt($value)
 * @mixin \Eloquent
 */
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

    public function newEloquentBuilder($query): SampleQueryBuilder
    {
        return new SampleQueryBuilder($query);
    }
}
