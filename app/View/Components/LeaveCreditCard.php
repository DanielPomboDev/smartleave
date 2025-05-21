<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class LeaveCreditCard extends Component
{
    public $type;
    public $balance;
    public $icon;
    public $bgColor;

    public function __construct($type, $balance, $icon, $bgColor = 'blue')
    {
        $this->type = $type;
        $this->balance = $balance;
        $this->icon = $icon;
        $this->bgColor = $bgColor;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.leave-credit-card');
    }
}
