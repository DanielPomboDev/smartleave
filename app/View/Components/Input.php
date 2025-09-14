<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\Contracts\View\View;

class Input extends Component
{
    public string $label;
    public string $type;
    public ?string $id;
    public ?string $name;
    public string $placeholder;
    public string $class;

    public function __construct(string $label, string $type = 'text', ?string $id = null, ?string $name = null, string $placeholder = '', string $class = '')
    {
        $this->label = $label;
        $this->type = $type;
        $this->id = $id ?? $name; // Default id to name if not provided
        $this->name = $name;
        $this->placeholder = $placeholder;
        $this->class = $class;
    }

    public function render(): View
    {
        return view('components.input');
    }
}