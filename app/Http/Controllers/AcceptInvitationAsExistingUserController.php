<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Invitation;
use Domain\Invitations\Actions\AcceptInvitationAction;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Contracts\Session\Session;
use Illuminate\Support\Facades\URL;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;

final class AcceptInvitationAsExistingUserController
{
    public function __invoke(Invitation $invitation, StatefulGuard $guard, AcceptInvitationAction $action, Session $session): View | Response
    {
        if (! $invitation->receiver) {
            return redirect(URL::signedRoute('invitations.accept.new', compact('invitation')));
        }

        if ($guard->check() && ! $guard->user()->is($invitation->receiver)) {
            $guard->logout();
        }

        if ($guard->check()) {
            $action->execute($invitation);

            return redirect(route('experiments.index'));
        }

        $session->put('invitation-id', $invitation->id);

        return view('admin.invitations.login-form', ['email' => $invitation->email]);
    }
}
