<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\AssayParameter;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method AssayParameter|AssayParameter[] create($attributes = [], ?Model $parent = null)
 */
final class AssayParameterFactory extends Factory
{
    /** @var string */
    protected $model = AssayParameter::class;

    public function definition(): array
    {
        $quantify = $this->faker->boolean();

        return [
            'assay_id' => AssayFactory::new(),
            'target' => $this->faker->word(),
            'required_repetitions' => $this->faker->numberBetween(1, 4),
            'cutoff' => $this->faker->randomFloat(2, 2, 40),
            'standard_deviation_cutoff' => $this->faker->randomFloat(2, 2, 40),
            'slope' => $quantify ? null : $this->faker->randomFloat(2, -1, 0),
            'intercept' => $quantify ? null : $this->faker->randomFloat(2, 2, 20),
        ];
    }
}
