<?php

namespace App\View\Components;

use Illuminate\Http\Request;
use Illuminate\View\Component;

class AdminNavigation extends Component
{
    public array $links;

    public bool $responsive;

    public function __construct(Request $request, bool $responsive = false)
    {
        $this->links = [
            [
                'href' => route('dashboard'),
                'label' => 'Dashboard',
                'icon' => 'heroicon-o-home',
                'active' => $request->is('dashboard'),
            ],
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
                'icon' => 'heroicon-o-chip',
                'active' => $request->is('assays*'),
            ],
            [
                'href' => route('quality-control.index'),
                'label' => 'Quality Control',
                'icon' => 'heroicon-o-clipboard',
                'active' => $request->is('quality-control/*'),
            ],
        ];
        $this->responsive = $responsive;
    }

    public function render()
    {
        return view('navigation.admin-navigation');
    }
}
