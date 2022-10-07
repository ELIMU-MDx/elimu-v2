<?php

namespace App\Console\Commands;

use Domain\Experiment\Enums\ImportStatus;
use Domain\Experiment\Jobs\ImportDataPointJob;
use Domain\Experiment\Models\Experiment;
use Illuminate\Console\Command;

final class ImportDataPointsCommand extends Command
{
    protected $signature = 'elimu:data-points:import';

    protected $description = 'Imports all data points for all experiments with the status pending';

    public function handle()
    {
        $count = 0;
        Experiment::where('import_status', ImportStatus::PENDING->name)
            ->lazy()
            ->each(function (Experiment $experiment) use (&$count) {
                dispatch(new ImportDataPointJob($experiment));
                $count++;
            });

        $this->info("Dispatched {$count} jobs");
    }
}
