<?php

declare(strict_types=1);

namespace App\Admin\Assays\Controllers;

use Illuminate\View\View;

final class CreateAssayController
{
    public function __invoke(): View
    {
        return view('admin.assays.create');
    }
}
