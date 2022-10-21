<?php

namespace Database\Factories;

use Domain\Experiment\Models\DataPoint;
use Illuminate\Database\Eloquent\Factories\Factory;

class DataPointFactory extends Factory
{
    protected $model = DataPoint::class;

    public function definition()
    {
        return [
            'cycle' => $this->faker->numberBetween(1, 1000),
        ];
    }
}
