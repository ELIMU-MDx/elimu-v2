<?php

declare(strict_types=1);

namespace App\Admin\Assays\Controllers;

use Domain\Assay\Models\Assay;
use Illuminate\View\View;

final class EditAssayController
{
    public function __invoke(Assay $assay): View
    {
        return view('admin.assays.edit', compact('assay'));
    }
}
