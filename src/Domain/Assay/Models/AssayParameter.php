<?php

declare(strict_types=1);

namespace Domain\Assay\Models;

use Domain\Assay\Collections\ParameterCollection;
use Illuminate\Database\Eloquent\Model;

final class AssayParameter extends Model
{
    protected $casts = [
        'slope' => 'float',
        'intercept' => 'float',
    ];

    public function newCollection(array $models = []): ParameterCollection
    {
        return ParameterCollection::make($models);
    }

}
