<?php

declare(strict_types=1);

namespace App\Admin\Experiments\Controllers;

use Domain\Experiment\Models\Experiment;
use Storage;
use Symfony\Component\HttpFoundation\Response;

final class DownloadRdmlController
{
    public function __invoke(Experiment $experiment): Response
    {
        return Storage::download($experiment->rdml_path, $experiment->name);
    }
}
