<?php

namespace App\Http\Controllers;

use App\Models\Sample;
use Illuminate\View\View;

final class ShowSampleController
{
    public function __invoke(Sample $sample): View
    {
        return view('samples.view', ['sample' => $sample]);
    }
}
