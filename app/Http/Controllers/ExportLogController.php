<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Domain\QualityControl\Exporters\ExportAuditLog;
use Illuminate\Contracts\Auth\Guard;
use Maatwebsite\Excel\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

final class ExportLogController
{
    public function __invoke(Excel $excel, Guard $guard): BinaryFileResponse
    {
        return $excel->download(
            new ExportAuditLog($guard->user()->study_id),
            $guard->user()->currentStudy->identifier.'-audit-log.xlsx'
        );
    }
}
