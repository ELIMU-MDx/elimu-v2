<?php

declare(strict_types=1);

use Database\Factories\InvitationFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

uses(WithFaker::class);

it('shows a registration form', function () {
    $invitation = InvitationFactory::new()->create();

    $this->disableSignatureValidation()
        ->get(route('invitations.accept.new', $invitation))
        ->assertSee('Name')
        ->assertSee('Password')
        ->assertSee('Confirm Password')
        ->assertSee('Register');
});

it('accepts a registration as a new user', function () {
    $invitation = InvitationFactory::new()->create();

    $password = '12345678';
    $this->disableSignatureValidation()
        ->post(route('invitations.accept.new', $invitation), [
            'name' => 'John Doe',
            'password' => $password,
            'password_confirmation' => $password,
        ])
        ->assertSessionHasNoErrors()
        ->assertRedirect();

    $this->assertDatabaseHas('users', [
        'email' => $invitation->email,
        'name' => 'John Doe',
        'study_id' => $invitation->study_id,
    ]);

    $this->assertDatabaseHas('study_user', [
        'study_id' => $invitation->study_id,
        'user_id' => $invitation->receiver->id,
        'role' => $invitation->role->identifier(),
    ]);

    $this->assertAuthenticated();
});
