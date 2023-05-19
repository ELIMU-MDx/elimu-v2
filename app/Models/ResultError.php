<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Stringable;

final class ResultError extends Model implements Stringable
{
    public function __toString(): string
    {
        return (string) $this->error;
    }

    public function result(): BelongsTo
    {
        return $this->belongsTo(Result::class);
    }
}
