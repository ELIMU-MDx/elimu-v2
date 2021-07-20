<?php

declare(strict_types=1);

namespace App\Admin\Studies\Controllers;

use Domain\Study\Models\Invitation;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;

final class AcceptInvitationController
{
    public function __invoke(Invitation $invitation): View
    {
        Auth::logout();

        if (!$invitation->receiver) {
            return view('studies.invitation-registration-form');
        }

        abort(401);

        // if new user show registration form
        // if existing user show login form
        // if logged in but not current email logout
        // if logged in and current email accept invitation
    }
}
