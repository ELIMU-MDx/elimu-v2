<?php

declare(strict_types=1);

use Database\Factories\UserFactory;
use Domain\Study\Models\Study;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;


it('creates a study', function () {
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
});

it('redirects an unauthenticated user', function () {
    $this->post('/studies', [
        'name' => 'New Study',
    ])->assertRedirect('/login');
});
