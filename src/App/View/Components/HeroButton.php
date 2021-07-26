<?php

namespace App\View\Components;

use Illuminate\View\Component;

class HeroButton extends Component
{
    public function __construct(public string $as = 'button')
    {
    }

    public function render()
    {
        return view('components.hero-button');
    }
}
