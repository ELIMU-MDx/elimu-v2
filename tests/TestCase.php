<?php

namespace Tests;

use Database\Factories\UserFactory;
use Domain\Users\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public function signIn(?User $user = null): static
    {
        return $this->actingAs($user ?? UserFactory::new()->create());
    }
}
