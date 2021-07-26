<?php

declare(strict_types=1);

namespace App\View\Components;

use Illuminate\View\Component;

class AdminNavigationLink extends Component
{
    public function __construct(public string $icon, public bool $active = false, public string $as = 'a',)
    {
    }

    public function render()
    {
        return view('navigation.admin-navigation-link');
    }
}
