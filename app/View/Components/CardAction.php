<?php

namespace App\View\Components;

use Illuminate\View\Component;

class CardAction extends Component
{
    public $title, $url, $name, $value;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($title, $url, $name, $value)
    {
        $this->title = $title;
        $this->url = $url;
        $this->name = $name;
        $this->value = $value;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.card-action');
    }
}
