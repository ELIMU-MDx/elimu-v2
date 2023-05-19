<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\AssayParameter;
use App\Models\Measurement;
use Domain\Rdml\Enums\MeasurementType;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method Measurement|Measurement[] create($attributes = [], ?Model $parent = null)
 */
final class MeasurementFactory extends Factory
{
    /** @var string */
    protected $model = Measurement::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'experiment_id' => ExperimentFactory::new(),
            'sample_id' => SampleFactory::new(),
            'cq' => $this->faker->boolean() ? $this->faker->randomFloat(2, 0, 10) : null,
            'target' => $this->faker->word(),
            'position' => strtoupper($this->faker->randomLetter()).$this->faker->randomNumber(2),
            'excluded' => $this->faker->boolean(),
            'type' => MeasurementType::SAMPLE,
        ];
    }

    public function forParameter(AssayParameter $parameter): static
    {
        return $this->state([
            'target' => $parameter->target,
        ]);
    }

    public function sample(): static
    {
        return $this->state(['type' => MeasurementType::SAMPLE]);
    }

    public function ntc(): static
    {
        return $this->state(['type' => MeasurementType::NTC_CONTROL]);
    }

    public function standard(): static
    {
        return $this->state(['type' => MeasurementType::STANDARD]);
    }

    public function included(): static
    {
        return $this->state(['excluded' => false]);
    }
}
