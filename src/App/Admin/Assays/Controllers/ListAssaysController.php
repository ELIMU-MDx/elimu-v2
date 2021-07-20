<?php

declare(strict_types=1);

namespace App\Admin\Assays\Controllers;

use Domain\Assay\Models\Assay;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class ListAssaysController
{
    public function __invoke(Request $request): View
    {
        $assays = Assay::where(
            function (Builder $builder) use ($request) {
                return $builder->where('study_id', $request->user()->study_id)
                    ->orWhereNull('study_id');
            })
            ->orderBy('name')
            ->with(['creator:id,name', 'study:id,name'])
            ->get()
            ->map(function (Assay $assay) {
                return (object) [
                    'id' => $assay->id,
                    'name' => $assay->name,
                    'sample_type' => $assay->sample_type,
                    'study' => $assay->study->name ?? null,
                    'created_by' => $assay->creator->name,
                    'created_at' => $assay->created_at,
                ];
            });

        return view('admin.assays.index', compact('assays'));
    }
}
