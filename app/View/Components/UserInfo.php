<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class UserInfo extends Component
{
    public $id;
    public $name;
    public $path;

    /**
     * Create a new component instance.
     */
    public function __construct($id = null, $name = null, $path = null)
    {
        $this->id = $id;
        $this->name = $name;
        $this->path = $path;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.user-info');
    }
}
