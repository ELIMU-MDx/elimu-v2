<?php

declare(strict_types=1);

namespace App\Admin\Experiments\Controllers;

use Illuminate\View\View;

final class ListResultsController
{
    public function __invoke(): View
    {
        return view('admin.results.index');
    }
}
