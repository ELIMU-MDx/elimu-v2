<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class CustomSecondaryButton extends Component
{
    public function __construct(public string $as = 'button')
    {
    }

    public function render(): View
    {
        return view('components.custom-secondary-button');
    }
}
