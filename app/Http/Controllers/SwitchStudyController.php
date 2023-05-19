<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Study;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class SwitchStudyController
{
    public function __invoke(Request $request, Study $study): Response
    {
        $user = $request->user();
        $user->study_id = $study->id;
        $user->save();

        return redirect()->back();
    }
}
