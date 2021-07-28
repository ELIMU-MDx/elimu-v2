<?php
declare(strict_types=1);

namespace Domain\QualityControl\Models;

use Auth;
use Domain\Study\Models\Study;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Collection;
use Spatie\Activitylog\Models\Activity as BaseActivity;

final class Activity extends BaseActivity
{

    public function study(): BelongsTo
    {
        return $this->belongsTo(Study::class);
    }

    public function attributes(): Collection
    {
        return collect($this->changes()->get('attributes'))
            ->map(function ($value, $key) {
                return "{$key}: {$value}";
            });
    }
    protected static function booted(): void
    {
        self::creating(function (Activity $activity) {
            $activity->study_id = Auth::user()->study_id;

            return $activity;
        });
    }
}
