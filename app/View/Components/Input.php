<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Input extends Component
{
    public function __construct(public string $type = 'text')
    {
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('form.input');
    }
}
