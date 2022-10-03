<?php

declare(strict_types=1);

namespace Database\Factories;

use Domain\Experiment\Models\Experiment;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method Experiment|Experiment[] create($attributes = [], ?Model $parent = null)
 */
final class ExperimentFactory extends Factory
{
    /** @var string */
    protected $model = Experiment::class;

    public function definition(): array
    {
        return [
            'study_id' => StudyFactory::new(),
            'assay_id' => AssayFactory::new(),
            'user_id' => UserFactory::new(),
            'name' => $this->faker->word(),
        ];
    }
}
