<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Experiment;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

final class DownloadRdmlController
{
    public function __invoke(Experiment $experiment): Response
    {
        return Storage::download($experiment->rdml_path, $experiment->name);
    }
}
