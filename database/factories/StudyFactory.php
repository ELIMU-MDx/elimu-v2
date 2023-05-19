<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Study;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method Study|Study[] create($attributes = [], ?Model $parent = null)
 */
final class StudyFactory extends Factory
{
    /** @var string */
    protected $model = Study::class;

    public function definition(): array
    {
        return [
            'identifier' => $this->faker->unique()->word(),
            'name' => $this->faker->word(),
        ];
    }
}
