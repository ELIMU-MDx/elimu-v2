<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Experiment;
use Illuminate\View\View;

final class ShowExperimentResultController
{
    public function __invoke(Experiment $experiment): View
    {
        return view('admin.experiments.results', compact('experiment'));
    }
}