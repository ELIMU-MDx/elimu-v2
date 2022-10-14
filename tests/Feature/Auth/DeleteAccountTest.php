<?php

use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Jetstream\Features;
use Laravel\Jetstream\Http\Livewire\DeleteUserForm;
use Livewire\Livewire;
use Tests\TestCase;

uses(TestCase::class);
uses(RefreshDatabase::class);

test('user accounts can be deleted', function () {
    if (! Features::hasAccountDeletionFeatures()) {
        return $this->markTestSkipped('Account deletion is not enabled.');
    }

    $this->actingAs($user = UserFactory::new()->create());

    $component = Livewire::test(DeleteUserForm::class)
                    ->set('password', 'password')
                    ->call('deleteUser');

    $this->assertNull($user->fresh());
});

test('correct password must be provided before account can be deleted', function () {
    if (! Features::hasAccountDeletionFeatures()) {
        return $this->markTestSkipped('Account deletion is not enabled.');
    }

    $this->actingAs($user = UserFactory::new()->create());

    Livewire::test(DeleteUserForm::class)
                    ->set('password', 'wrong-password')
                    ->call('deleteUser')
                    ->assertHasErrors(['password']);

    $this->assertNotNull($user->fresh());
});
