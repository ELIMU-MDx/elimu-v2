<?php

namespace App\Http\Controllers;

use App\Models\Assay;
use Carbon\CarbonInterval;
use Domain\Assay\Jobs\CreateSampleReportsJob;
use Illuminate\Support\Facades\Auth;

final class DownloadAssayReportsController
{
    public function __invoke(Assay $assay)
    {
        dispatch_sync(new CreateSampleReportsJob($assay, Auth::user()->email));

        $numberOfResults = $assay->results()->count();

        $durationEstimate = CarbonInterval::seconds(5 * $numberOfResults);

        return 'You will receive a mail with the reports within about '.$durationEstimate->totalMinutes.' minutes.';
    }
}
