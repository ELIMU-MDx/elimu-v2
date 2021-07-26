<?php

declare(strict_types=1);

namespace Tests\Feature\Studies;

use Database\Factories\UserFactory;
use Domain\Study\Models\Study;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class CreateStudyTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function itCreatesAStudy(): void
    {
        $user = UserFactory::new()->create();

        $this->signIn($user)
            ->post('/studies', [
                'identifier' => 'study-xyz',
                'name' => 'New Study',
            ])
            ->assertSessionHasNoErrors()
            ->assertRedirect('/current-study/settings');

        $this->assertDatabaseHas(
            'studies',
            ['name' => 'New Study']
        );

        $this->assertDatabaseHas(
            'users',
            [
                'id' => $user->id,
                'study_id' => Study::latest()->first()->id,
            ]
        );
    }

    /** @test */
    public function itRedirectsAnUnauthenticatedUser(): void
    {
        $this->post('/studies', [
            'name' => 'New Study',
        ])->assertRedirect('/login');
    }
}
