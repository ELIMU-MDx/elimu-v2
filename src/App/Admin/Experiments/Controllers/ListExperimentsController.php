<?php

declare(strict_types=1);

namespace App\Admin\Experiments\Controllers;

use Domain\Experiment\Models\Experiment;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\View\View;

final class ListExperimentsController
{
    public function __invoke(Guard $guard): View
    {
        $experiments = Experiment::where('study_id', $guard->user()->study_id)
            ->withSamplesCount()
            ->with('controls.result.resultErrors')
            ->orderByDesc('created_at')
            ->get();

        return view('admin.experiments.index', compact('experiments'));
    }
}
