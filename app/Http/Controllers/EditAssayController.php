<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Assay;
use Illuminate\View\View;

final class EditAssayController
{
    public function __invoke(Assay $assay): View
    {
        return view('admin.assays.edit', compact('assay'));
    }
}
