<?php

declare(strict_types=1);

namespace Database\Factories;

use Domain\Assay\Models\Assay;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method Assay|Assay[] create($attributes = [], ?Model $parent = null)
 */
final class AssayFactory extends Factory
{
    /** @var string */
    protected $model = Assay::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->word,
            'sample_type' => $this->faker->word,
            'user_id' => UserFactory::new(),
        ];
    }

    public function hasParameters(AssayParameterFactory $factory): static
    {
        return $this->has($factory, 'parameters');
    }
}
