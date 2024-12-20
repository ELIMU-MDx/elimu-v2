<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Invitation;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\URL;
use Symfony\Component\HttpFoundation\Response;

final class AcceptInvitationAsNewUserController
{
    public function __invoke(Invitation $invitation, StatefulGuard $guard): View|Response
    {
        $guard->logout();

        if ($invitation->receiver) {
            return redirect(URL::signedRoute('invitations.accept.existing', compact('invitation')));
        }

        return view('admin.invitations.registration-form', ['email' => $invitation->email]);
    }
}
