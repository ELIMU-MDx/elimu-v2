<?php

namespace App\View\Components;

use Illuminate\View\Component;

class PdfLayout extends Component
{
    public function render()
    {
        return view('layouts.pdf');
    }
}
