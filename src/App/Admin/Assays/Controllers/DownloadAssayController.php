<?php

namespace App\Admin\Assays\Controllers;

use Domain\Assay\Exporters\AssayExcelExporter;
use Domain\Assay\Models\Assay;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

final class DownloadAssayController
{
    public function __invoke(Assay $assay): BinaryFileResponse
    {
        return Excel::download(new AssayExcelExporter($assay->id), Str::slug($assay->name).'.xlsx');
    }
}
