<?php

declare(strict_types=1);

namespace App\Admin\Experiments\Controllers;

use App\Admin\Experiments\Requests\UpdateExperimentRequest;
use Domain\Experiment\Models\Experiment;
use Symfony\Component\HttpFoundation\Response;

final class UpdateExperimentController
{
    public function __invoke(Experiment $experiment, UpdateExperimentRequest $request): Response
    {
        $experiment->fill($request->validated());
        $experiment->save();

        return redirect(route('experiments.index'));
    }
}
