<?php

declare(strict_types=1);

namespace App\Admin\Studies\Controllers;

use App\Admin\Studies\Requests\RegisterWithInvitationRequest;
use Domain\Study\Actions\CreateStudyMemberAction;
use Domain\Study\DataTransferObjects\StudyMemberParameters;
use Domain\Study\Models\Invitation;
use Illuminate\Contracts\Auth\StatefulGuard;
use Symfony\Component\HttpFoundation\Response;

final class RegisterWithInvitationController
{
    public function __invoke(
        Invitation $invitation,
        RegisterWithInvitationRequest $request,
        CreateStudyMemberAction $action,
        StatefulGuard $guard
    ): Response {
        $user = $action->execute($invitation, new StudyMemberParameters($request->validated()));

        $guard->login($user);

        return redirect(route('dashboard'));
    }
}
