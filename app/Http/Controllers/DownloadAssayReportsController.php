<?php

namespace App\Http\Controllers;

use App\Models\Assay;
use Domain\Assay\Jobs\CreateSampleReportsJob;
use Illuminate\Support\Facades\Auth;

final class DownloadAssayReportsController
{
    public function __invoke(Assay $assay)
    {
        dispatch_sync(new CreateSampleReportsJob($assay, Auth::user()->email));

        return 'You will receive a mail with the reports within the next few minutes.';
    }
}
