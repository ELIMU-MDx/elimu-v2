<?php

declare(strict_types=1);

namespace Domain\Experiment\Actions;

use Auth;
use Domain\Experiment\Models\Experiment;
use Domain\Experiment\Models\Measurement;
use Domain\Experiment\Models\Sample;
use Domain\Results\Models\Result;

final class DeleteExperimentAction
{
    public function __construct(private RecalculateResultsAction $action)
    {
    }

    public function execute(Experiment $experiment): void
    {
        $experiment->delete();
        Result::whereDoesntHave('measurements')->delete();
        Sample::whereDoesntHave('results')->delete();

        $this->action
            ->execute(Measurement::whereHas('experiment', function ($join) use ($experiment) {
                return $join
                    ->where('study_id', $experiment->study_id)
                    ->where('assay_id', $experiment->assay_id);
            })->get());
    }
}
