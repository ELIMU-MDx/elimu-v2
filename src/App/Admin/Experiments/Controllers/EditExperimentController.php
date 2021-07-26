<?php

declare(strict_types=1);

namespace App\Admin\Experiments\Controllers;

use Domain\Experiment\Models\Experiment;
use Illuminate\View\View;

final class EditExperimentController
{
    public function __invoke(Experiment $experiment): View
    {
        return view('admin.experiments.edit', compact('experiment'));
    }
}
