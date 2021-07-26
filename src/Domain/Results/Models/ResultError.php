<?php

declare(strict_types=1);

namespace Domain\Results\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Domain\Results\Models\ResultError
 *
 * @property int $id
 * @property int $result_id
 * @property string $error
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @property-read \Domain\Results\Models\Result $result
 *
 * @method static \Illuminate\Database\Eloquent\Builder|ResultError newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ResultError newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ResultError query()
 * @method static \Illuminate\Database\Eloquent\Builder|ResultError whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ResultError whereError($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ResultError whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ResultError whereResultId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ResultError whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
final class ResultError extends Model
{
    public function __toString()
    {
        return (string) $this->error;
    }

    public function result(): BelongsTo
    {
        return $this->belongsTo(Result::class);
    }
}
