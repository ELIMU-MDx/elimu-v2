<?php

namespace App\View\Components;

use Illuminate\View\View;
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
     *
     * @return View
     */
    public function render()
    {
        return view('layouts.app');
    }
}
