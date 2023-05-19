<?php

declare(strict_types=1);

namespace App\Http\Controllers;

final class ListExperimentsController
{
    public function __invoke()
    {
        return view('admin.experiments.index');
    }
}
