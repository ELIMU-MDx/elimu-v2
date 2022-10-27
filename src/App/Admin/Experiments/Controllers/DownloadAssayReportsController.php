<?php

namespace App\Admin\Experiments\Controllers;

use Domain\Assay\Jobs\CreateSampleReportsJob;
use Domain\Assay\Models\Assay;
use Illuminate\Support\Facades\Auth;

final class DownloadAssayReportsController
{
    public function __invoke(Assay $assay)
    {
        dispatch(new CreateSampleReportsJob($assay, Auth::user()->email));

        return 'You will receive a mail with the reports within the next few minutes.';
    }
}
