<?php

declare(strict_types=1);

use App\View\Livewire\StudyMemberManager;
use Database\Factories\UserFactory;
use Domain\Study\Mailable\NewUserInvitationMail;
use Domain\Study\Roles\Scientist;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

uses(TestCase::class);
uses(RefreshDatabase::class);

it('invites a user', function () {
    $user = UserFactory::new()->withStudy()->create();
    Mail::fake();

    Livewire::actingAs($user)
        ->test(StudyMemberManager::class, ['study' => $user->currentStudy])
        ->set('addMemberForm.email', 'foo@bar.ch')
        ->set('addMemberForm.role', (new Scientist())->identifier())
        ->call('addMember');

    $this->assertDatabaseHas('invitations', [
        'study_id' => $user->study_id,
        'user_id' => $user->id,
        'email' => 'foo@bar.ch',
        'role' => (new Scientist())->identifier(),
    ]);
    Mail::assertQueued(function (NewUserInvitationMail $mail) {
        return $mail->hasTo('foo@bar.ch');
    });
});
