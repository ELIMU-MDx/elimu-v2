<?php

declare(strict_types=1);

namespace Domain\Experiment\Actions;

use App\Models\Experiment;
use App\Models\Measurement;
use App\Models\Result;
use App\Models\Sample;

final class DeleteExperimentAction
{
    public function __construct(private readonly RecalculateResultsAction $action)
    {
    }

    public function execute(int $experimentId): void
    {
        $experiment = Experiment::find($experimentId);
        $experiment->delete();
        Result::whereDoesntHave('measurements')->delete();
        Sample::whereDoesntHave('results')->delete();

        $this->action
            ->execute(Measurement::whereHas('experiment', fn($join) => $join
                ->where('study_id', $experiment->study_id)
                ->where('assay_id', $experiment->assay_id))->get());
    }
}
