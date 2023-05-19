<?php

declare(strict_types=1);

namespace App\Models;

use Domain\Assay\Collections\ParameterCollection;
use Illuminate\Database\Eloquent\Model;

final class AssayParameter extends Model
{
    protected $casts = [
        'slope' => 'float',
        'intercept' => 'float',
        'cutoff' => 'float',
        'is_control' => 'bool',
    ];

    protected $appends = ['quantify'];

    public function getQuantifyAttribute(): bool
    {
        return $this->slope !== null && $this->intercept !== null;
    }

    public function newCollection(array $models = []): ParameterCollection
    {
        return ParameterCollection::make($models);
    }
}
