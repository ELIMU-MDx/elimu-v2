<?php

declare(strict_types=1);

namespace App\Admin\Studies\Controllers;

use Illuminate\View\View;

final class CreateFirstStudyController
{
    public function __invoke(): View
    {
        return view('studies.create-first');
    }
}
