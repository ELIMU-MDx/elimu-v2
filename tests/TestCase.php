<?php

namespace Tests;

use Database\Factories\UserFactory;
use Domain\Users\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Routing\Middleware\ValidateSignature;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();

        activity()->disableLogging();
    }

    public function signIn(?User $user = null): static
    {
        return $this->actingAs($user ?? UserFactory::new()->create());
    }

    public function disableSignatureValidation(): static
    {
        return $this->withoutMiddleware(ValidateSignature::class);
    }
}
