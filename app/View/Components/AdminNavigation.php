<?php

namespace App\View\Components;

use Illuminate\Http\Request;
use Illuminate\View\Component;

class AdminNavigation extends Component
{
    public array $links;

    public function __construct(Request $request, public bool $responsive = false)
    {
        $this->links = [
            [
                'href' => route('experiments.index'),
                'label' => 'Experiments',
                'icon' => 'heroicon-o-beaker',
                'active' => $request->is('experiments*'),
            ],
            [
                'href' => route('results.index'),
                'label' => 'Results',
                'icon' => 'heroicon-o-chart-pie',
                'active' => $request->is('results*'),
            ],
            [
                'href' => url('/assays'),
                'label' => 'Assays',
                'icon' => 'heroicon-o-cpu-chip',
                'active' => $request->is('assays*'),
            ],
            [
                'href' => route('quality-control.index'),
                'label' => 'Quality Control',
                'icon' => 'heroicon-o-clipboard',
                'active' => $request->is('quality-control/*'),
            ],
        ];
    }

    public function render()
    {
        return view('navigation.admin-navigation');
    }
}
