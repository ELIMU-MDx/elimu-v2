<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Sample;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method Sample|Sample[] create($attributes = [], ?Model $parent = null)
 */
final class SampleFactory extends Factory
{
    /** @var string */
    protected $model = Sample::class;

    public function definition(): array
    {
        return [
            'study_id' => StudyFactory::new(),
            'identifier' => $this->faker->unique()->word(),
        ];
    }

    public function withAllData(): static
    {
        return $this->has(MeasurementFactory::new()->has(DataPointFactory::new()));
    }
}
