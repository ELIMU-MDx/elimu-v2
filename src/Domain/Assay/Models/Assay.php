<?php

declare(strict_types=1);

namespace Domain\Assay\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class Assay extends Model
{
    public function parameters(): HasMany
    {
        return $this->hasMany(AssayParameter::class);
    }
}
