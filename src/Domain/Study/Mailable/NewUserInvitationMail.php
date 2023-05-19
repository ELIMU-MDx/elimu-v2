<?php

declare(strict_types=1);

namespace Domain\Study\Mailable;

use App\Models\Invitation;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\URL;

final class NewUserInvitationMail extends Mailable implements ShouldQueue
{
    public Invitation $invitation;

    public string $acceptUrl;

    public function __construct(Invitation $invitation)
    {
        $this->invitation = $invitation;
        $this->acceptUrl = URL::signedRoute(
            $invitation->receiver ? 'invitations.accept.existing' : 'invitations.accept.new',
            compact('invitation')
        );
        $this->to($this->invitation->email);
    }

    public function build(): NewUserInvitationMail
    {
        return $this
            ->markdown('studies.email.new-user-invitation');
    }
}
