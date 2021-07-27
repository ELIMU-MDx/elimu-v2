<?php

declare(strict_types=1);

namespace App\Admin\Experiments\Controllers;

use Domain\Assay\Models\Assay;
use Domain\Results\Exports\ResultExcelExport;
use Illuminate\Contracts\Auth\StatefulGuard;
use Maatwebsite\Excel\Excel;
use Symfony\Component\HttpFoundation\Response;

final class ExportResultsController
{
    /**
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function __invoke(Assay $assay, StatefulGuard $guard, Excel $excel): Response
    {
        return $excel->download(new ResultExcelExport($assay, $guard->user()), $assay->name . '-results.xlsx');
    }
}
