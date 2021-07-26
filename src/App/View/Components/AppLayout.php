<?php

declare(strict_types=1);

namespace App\View\Components;

use Illuminate\Http\Request;
use Illuminate\View\Component;

class AppLayout extends Component
{
    public bool $navigation;

    public function __construct(Request $request, bool $navigation = true)
    {
        $this->navigation = $request->user()->study_id && $navigation;
    }

    /**
     * Get the view / contents that represents the component.
     */
    public function render(): \Illuminate\View\View
    {
        return view('layouts.app');
    }
}
