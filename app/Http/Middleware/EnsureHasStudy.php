<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class EnsureHasStudy implements Middleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! User::isMemberOfAnyStudy($request->user()->id)) {
            return redirect()->route('studies.create-first');
        }

        $this->ensureOneOfTheTeamsIsCurrent($request->user());

        return $next($request);
    }

    protected function ensureOneOfTheTeamsIsCurrent(User $user): void
    {
        if (! is_null($user->study_id)) {
            return;
        }

        $firstStudyId = $user->studies()->first()->id;
        $user->update(['study_id' => $firstStudyId]);
    }
}
