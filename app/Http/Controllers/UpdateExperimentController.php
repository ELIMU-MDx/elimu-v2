<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\UpdateExperimentRequest;
use App\Models\Experiment;
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
