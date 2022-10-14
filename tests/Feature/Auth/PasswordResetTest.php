<?php

use Database\Factories\UserFactory;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Laravel\Fortify\Features;
use Tests\TestCase;

uses(TestCase::class);
uses(RefreshDatabase::class);

test('reset password link screen can be rendered', function () {
    if (! Features::enabled(Features::updatePasswords())) {
        return $this->markTestSkipped('Password updates are not enabled.');
    }

    $response = $this->get('/forgot-password');

    $response->assertStatus(200);
});

test('reset password link can be requested', function () {
    if (! Features::enabled(Features::updatePasswords())) {
        return $this->markTestSkipped('Password updates are not enabled.');
    }

    Notification::fake();

    $user = UserFactory::new()->create();

    $response = $this->post('/forgot-password', [
        'email' => $user->email,
    ]);

    Notification::assertSentTo($user, ResetPassword::class);
});

test('reset password screen can be rendered', function () {
    if (! Features::enabled(Features::updatePasswords())) {
        return $this->markTestSkipped('Password updates are not enabled.');
    }

    Notification::fake();

    $user = UserFactory::new()->create();

    $response = $this->post('/forgot-password', [
        'email' => $user->email,
    ]);

    Notification::assertSentTo($user, ResetPassword::class, function ($notification) {
        $response = $this->get('/reset-password/'.$notification->token);

        $response->assertStatus(200);

        return true;
    });
});

test('password can be reset with valid token', function () {
    if (! Features::enabled(Features::updatePasswords())) {
        return $this->markTestSkipped('Password updates are not enabled.');
    }

    Notification::fake();

    $user = UserFactory::new()->create();

    $response = $this->post('/forgot-password', [
        'email' => $user->email,
    ]);

    Notification::assertSentTo($user, ResetPassword::class, function ($notification) use ($user) {
        $response = $this->post('/reset-password', [
            'token' => $notification->token,
            'email' => $user->email,
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertSessionHasNoErrors();

        return true;
    });
});
