<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Invitation;
use Domain\Study\Roles\RoleFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method Invitation|Invitation[] create($attributes = [], ?Model $parent = null)
 */
final class InvitationFactory extends Factory
{
    /** @var string */
    protected $model = Invitation::class;

    public function definition(): array
    {
        return [
            'study_id' => StudyFactory::new(),
            'user_id' => UserFactory::new(),
            'email' => $this->faker->email(),
            'role' => $this->faker->randomElement(RoleFactory::all()->toArray()),
        ];
    }
}
