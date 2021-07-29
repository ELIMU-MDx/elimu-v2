<?php

declare(strict_types=1);

namespace Database\Factories;

use Domain\Experiment\Models\Measurement;
use Domain\Results\Enums\QualitativeResult;
use Domain\Results\Models\Result;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method Result|Result[] create($attributes = [], ?Model $parent = null)
 */
final class ResultFactory extends Factory
{
    /** @var string */
    protected $model = Result::class;

    public function definition(): array
    {
        $qualification = $this->faker->randomElement([QualitativeResult::POSITIVE(), QualitativeResult::NEGATIVE()]);

        return [
            'sample_id' => SampleFactory::new(),
            'assay_id' => AssayFactory::new(),
            'target' => $this->faker->word,
            'cq' => $this->faker->boolean ? $this->faker->randomFloat(nbMaxDecimals: 2, max: 9999) : null,
            'quantification' => $qualification === QualitativeResult::POSITIVE() ? $this->faker->randomFloat(nbMaxDecimals: 2, max: 9999) : null,
            'qualification' => $qualification,
            'standard_deviation' => $this->faker->randomFloat(nbMaxDecimals: 2, max: 9999),
        ];
    }

    public function withMeasurement(): ResultFactory
    {
        return $this->afterCreating(function(Result $result) {
            MeasurementFactory::new(['cq' => $result->cq, 'target' => $result->target, 'sample_id' => $result->sample_id, 'result_id' => $result->id])->create();
        });
    }
}
