<?php

namespace Domain\Experiment\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class DataPoint extends Model
{
    public function measurement(): BelongsTo
    {
        return $this->belongsTo(Measurement::class);
    }
}
