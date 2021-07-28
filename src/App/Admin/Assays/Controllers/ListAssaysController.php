<?php

declare(strict_types=1);

namespace App\Admin\Assays\Controllers;

use Domain\Assay\Models\Assay;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class ListAssaysController
{
    public function __invoke(Request $request): View
    {
        $assays = Assay::where('study_id', $request->user()->study_id)
            ->orderBy('name')
            ->with(['parameters'])
            ->get();

        return view('admin.assays.index', compact('assays'));
    }
}
