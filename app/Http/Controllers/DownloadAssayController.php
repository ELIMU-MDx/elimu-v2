<?php

namespace App\Http\Controllers;

use App\Models\Assay;
use Domain\Assay\Exporters\AssayExcelExporter;
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
