<?php

declare(strict_types=1);

namespace App\Admin\Assays\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class ListAssaysController
{
    public function __invoke(Request $request): View
    {
        $assays = [
            (object) [
                'name' => 'PlasQ_RDT_V1-2',
                'sample_type' => 'RDT_2019',
                'study' => null,
                'created_by' => 'Silvan',
                'created_at' => Carbon::now(),
            ],
            (object) [
                'name' => 'PlasQ_RDT_V1-2',
                'sample_type' => 'RDT_2019',
                'study' => 'EGbyRDT',
                'created_by' => 'Silvan',
                'created_at' => Carbon::now(),
            ],
            (object) [
                'name' => 'PlasQ_RDT_V1-2',
                'sample_type' => 'RDT_2019',
                'study' => 'EGbyRDT',
                'created_by' => 'Silvan',
                'created_at' => Carbon::now(),
            ],
        ];

        return view('admin.assays.index', compact('assays'));
    }
}
