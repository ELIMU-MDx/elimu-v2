<?php

declare(strict_types=1);

namespace Domain\Assay\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Domain\Assay\Models\Assay
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Assay newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Assay newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Assay query()
 * @method static \Illuminate\Database\Eloquent\Builder|Assay whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Assay whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Assay whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Assay whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\Domain\Assay\Models\AssayParameter[] $parameters
 * @property-read int|null $parameters_count
 */
final class Assay extends Model
{
    public function parameters(): HasMany
    {
        return $this->hasMany(AssayParameter::class);
    }
}
