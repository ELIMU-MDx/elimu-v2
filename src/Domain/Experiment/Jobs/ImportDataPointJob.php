<?php

namespace Domain\Experiment\Jobs;

use App\Models\Experiment;
use Domain\Experiment\Actions\ImportDataPointsAction;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

final class ImportDataPointJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(private Experiment $experiment)
    {
    }

    public function handle(ImportDataPointsAction $action)
    {
        $action->execute($this->experiment);
    }
}
