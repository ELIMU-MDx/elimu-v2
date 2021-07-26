<?php

declare(strict_types=1);

namespace Domain\Experiment\Models;

use Domain\Experiment\Collections\MeasurementCollection;
use Domain\Rdml\Enums\MeasurementType;
use Domain\Results\Models\Result;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Kirschbaum\PowerJoins\PowerJoins;

final class Measurement extends Model
{
    use PowerJoins;

    protected $casts = [
        'type' => MeasurementType::class,
        'excluded' => 'bool',
    ];

    public function experiment(): BelongsTo
    {
        return $this->belongsTo(Experiment::class);
    }

    public function sample(): BelongsTo
    {
        return $this->belongsTo(Sample::class);
    }

    public function result(): BelongsTo
    {
        return $this->belongsTo(Result::class);
    }

    public function newCollection(array $models = []): MeasurementCollection
    {
        return MeasurementCollection::make($models);
    }

}
