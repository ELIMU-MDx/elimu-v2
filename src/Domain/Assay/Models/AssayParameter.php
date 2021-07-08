<?php

declare(strict_types=1);

namespace Domain\Assay\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Domain\Assay\Models\AssayParameter
 *
 * @property int $id
 * @property int $assay_id
 * @property string $target
 * @property int $required_repetitions
 * @property float $cutoff
 * @property float $standard_deviation_cutoff
 * @property float|null $slope
 * @property float|null $intercept
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|AssayParameter newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AssayParameter newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AssayParameter query()
 * @method static \Illuminate\Database\Eloquent\Builder|AssayParameter whereAssayId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AssayParameter whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AssayParameter whereCutoff($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AssayParameter whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AssayParameter whereIntercept($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AssayParameter whereRequiredRepetitions($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AssayParameter whereSlope($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AssayParameter whereStandardDeviationCutoff($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AssayParameter whereTarget($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AssayParameter whereUpdatedAt($value)
 * @mixin \Eloquent
 */
final class AssayParameter extends Model
{

}
