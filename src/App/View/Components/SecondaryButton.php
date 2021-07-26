<?php

declare(strict_types=1);

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class SecondaryButton extends Component
{
    public function __construct(public string $as = 'button')
    {
    }

    public function render(): View
    {
        return view('components.secondary-button');
    }
}
