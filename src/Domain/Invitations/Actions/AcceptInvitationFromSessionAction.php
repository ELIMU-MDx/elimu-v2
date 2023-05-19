<?php

declare(strict_types=1);

namespace Domain\Invitations\Actions;

use App\Models\Invitation;
use Illuminate\Http\Request;

final class AcceptInvitationFromSessionAction
{
    public function __construct(private readonly AcceptInvitationAction $acceptInvitationAction)
    {
    }

    public function handle(Request $request, callable $next)
    {
        if (! $request->getSession()->has('invitation-id')) {
            return $next($request);
        }

        $invitation = Invitation::firstWhere('id', $request->getSession()->get('invitation-id'));

        if (! $invitation) {
            return $next($request);
        }

        if ($request->user()->email !== $invitation->email) {
            return $next($request);
        }

        $this->acceptInvitationAction->execute($invitation);

        return $next($request);
    }
}
