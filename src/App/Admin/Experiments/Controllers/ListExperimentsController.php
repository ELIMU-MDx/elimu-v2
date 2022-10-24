<?php

declare(strict_types=1);

namespace App\Admin\Experiments\Controllers;

final class ListExperimentsController
{
    public function __invoke()
    {
        return view('admin.experiments.index');
    }
}
