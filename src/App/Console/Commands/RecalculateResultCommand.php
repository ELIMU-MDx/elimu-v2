<?php

namespace App\Console\Commands;

use Domain\Experiment\Actions\RecalculateResultsAction;
use Domain\Experiment\Models\Measurement;
use Illuminate\Console\Command;

class RecalculateResultCommand extends Command
{
    protected $signature = 'elimu:recalculate {studyId}';

    protected $description = 'Recalculates the results for a study';

    public function handle(RecalculateResultsAction $action): int
    {
        $measurements = Measurement::whereHas('sample', fn ($query) => $query->where('study_id', $this->argument('studyId')))
            ->get();

        $this->info("Recaluclate results for {$measurements->count()} measurements");

        $action->execute($measurements);

        $this->info('Successfully recalculated results');

        return 0;
    }
}
