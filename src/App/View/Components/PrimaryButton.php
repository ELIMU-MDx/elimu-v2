<?php

declare(strict_types=1);

namespace App\View\Components;

use Illuminate\View\Component;

class PrimaryButton extends Component
{
    public function __construct(public string $as = 'button')
    {
    }

    public function render()
    {
        return view('components.primary-button');
    }
}
