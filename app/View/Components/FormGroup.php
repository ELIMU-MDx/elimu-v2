<?php

namespace App\View\Components;

use Illuminate\View\Component;

class FormGroup extends Component
{
    public string $label;

    public string $name;

    public string $id;

    public string $type;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(string $label, string $name, string $type = 'text')
    {
        $this->label = $label;
        $this->name = $name;
        $this->id = 'input_'.$name;
        $this->type = $type;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('form.group');
    }
}
