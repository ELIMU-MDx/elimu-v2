<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Invitation;
use Domain\Invitations\Actions\AcceptInvitationAction;
use Domain\Users\Actions\CreateNewUser;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class RegisterWithInvitationController
{
    public function __invoke(
        Invitation $invitation,
        Request $request,
        CreateNewUser $createNewUserAction,
        AcceptInvitationAction $acceptInvitationAction,
        StatefulGuard $guard
    ): Response {
        $user = $createNewUserAction->create($request->merge(['email' => $invitation->email])->all());
        $guard->login($user);
        $acceptInvitationAction->execute($invitation);

        return redirect(route('experiments.index'));
    }
}
