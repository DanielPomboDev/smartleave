<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class SidebarItem extends Component
{
    public $label;
    public $icon;
    public $route;
    public $isActive;

    public function __construct($label, $icon, $route)
    {
        $this->label = $label;
        $this->icon = $icon;
        $this->route = $route;
        $this->isActive = request()->is(ltrim($route, '/'));
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.sidebar-item');
    }
}

