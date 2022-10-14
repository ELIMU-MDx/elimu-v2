<?php

use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Laravel\Jetstream\Features;
use Laravel\Jetstream\Http\Livewire\ApiTokenManager;
use Livewire\Livewire;
use Tests\TestCase;

uses(TestCase::class);
uses(RefreshDatabase::class);

test('api tokens can be deleted', function () {
    if (! Features::hasApiFeatures()) {
        return $this->markTestSkipped('API support is not enabled.');
    }

    if (Features::hasTeamFeatures()) {
        $this->actingAs($user = UserFactory::new()->withPersonalTeam()->create());
    } else {
        $this->actingAs($user = UserFactory::new()->create());
    }

    $token = $user->tokens()->create([
        'name' => 'Test Token',
        'token' => Str::random(40),
        'abilities' => ['create', 'read'],
    ]);

    Livewire::test(ApiTokenManager::class)
                ->set(['apiTokenIdBeingDeleted' => $token->id])
                ->call('deleteApiToken');

    expect($user->fresh()->tokens)->toHaveCount(0);
});
