<?php

declare(strict_types=1);

namespace App\Admin\QualityControl\Controllers;

use Domain\QualityControl\Models\Activity;
use Illuminate\Contracts\Auth\StatefulGuard;

final class ListLogController
{
    public function __invoke(StatefulGuard $guard)
    {
        $logs = Activity::latest()->where('study_id', $guard->user()->study_id)->with(['causer:id,name'])->paginate();

        return view('admin.quality_control.index', compact('logs'));
    }
}
