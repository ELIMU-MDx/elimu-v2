<?php

declare(strict_types=1);

namespace Tests\Feature\Studies;

use App\View\Livewire\StudyMemberManager;
use Database\Factories\UserFactory;
use Domain\Study\Mailable\NewUserInvitationMail;
use Domain\Study\Roles\Scientist;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Livewire;
use Tests\TestCase;

final class InviteNewUserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function itInvitesAUser(): void
    {
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
    }
}
