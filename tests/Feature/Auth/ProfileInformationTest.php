<?php

use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Jetstream\Http\Livewire\UpdateProfileInformationForm;
use Livewire\Livewire;
use Tests\TestCase;

uses(TestCase::class);
uses(RefreshDatabase::class);

test('current profile information is available', function () {
    $this->actingAs($user = UserFactory::new()->create());

    $component = Livewire::test(UpdateProfileInformationForm::class);

    expect($component->state['name'])->toEqual($user->name);
    expect($component->state['email'])->toEqual($user->email);
});

test('profile information can be updated', function () {
    $this->actingAs($user = UserFactory::new()->create());

    Livewire::test(UpdateProfileInformationForm::class)
            ->set('state', ['name' => 'Test Name', 'email' => 'test@example.com'])
            ->call('updateProfileInformation');

    expect($user->fresh()->name)->toEqual('Test Name');
    expect($user->fresh()->email)->toEqual('test@example.com');
});
