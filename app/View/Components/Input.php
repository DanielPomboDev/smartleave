<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Input extends Component
{
    public $label;
    public $type;
    public $id;
    public $name;
    public $placeholder;
    public $class;

    public function __construct($label, $type = 'text', $id = null, $name = null, $placeholder = '', $class = '')
    {
        $this->label = $label;
        $this->type = $type;
        $this->id = $id ?? $name; // Default id to name if not provided
        $this->name = $name;
        $this->placeholder = $placeholder;
        $this->class = $class;
    }

    public function render()
    {
        return view('components.input');
    }
}

