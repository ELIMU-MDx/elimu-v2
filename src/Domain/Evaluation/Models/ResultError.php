<?php

declare(strict_types=1);

namespace Domain\Evaluation\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class ResultError extends Model
{
    public function result(): BelongsTo
    {
        return $this->belongsTo(Result::class);
    }

    public function __toString()
    {
        return (string) $this->error;
    }
}
