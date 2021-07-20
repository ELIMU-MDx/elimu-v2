<?php

declare(strict_types=1);

namespace Domain\Experiment\Models;

use Domain\Experiment\Collections\MeasurementCollection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Kirschbaum\PowerJoins\PowerJoins;

final class Measurement extends Model
{
    use PowerJoins;

    public function experiment(): BelongsTo
    {
        return $this->belongsTo(Experiment::class);
    }

    public function sample(): BelongsTo
    {
        return $this->belongsTo(Sample::class);
    }

    public function newCollection(array $models = []): MeasurementCollection
    {
        return MeasurementCollection::make($models);
    }

}
