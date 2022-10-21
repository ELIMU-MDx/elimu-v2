<?php

namespace Domain\Experiment\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class QuantifyParameter extends Model
{
    protected $casts = [
        'slope' => 'float',
        'intercept' => 'float',
    ];

    public function experiment(): BelongsTo
    {
        return $this->belongsTo(Experiment::class);
    }

    public function calculateE(): float
    {
        return round(1 - (10 ** (-1 / $this->slope)), 4) * -100;
    }
}
