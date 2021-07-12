<?php

declare(strict_types=1);

namespace App\Admin\Studies\Controllers;

use App\Admin\Studies\Requests\SwitchStudyRequest;
use Domain\Study\Models\Membership;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\RedirectResponse;

final class SwitchStudyController
{
    public function __invoke(SwitchStudyRequest $request, Builder $builder): RedirectResponse
    {
        /** @var \Domain\Users\Models\User $user */
        $user = $request->user();

        if (Membership::selectRaw(1)
            ->where('user_id', $user->id)
            ->where('study_id', $request->input('study_id'))
            ->get()
            ->isEmpty()
        ) {
            abort(401);
        }

        $user->study_id = $request->input('study_id');
        $user->save();

        return redirect()->back();
    }
}
