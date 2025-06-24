<?php

namespace App\View\Components;

use Illuminate\View\Component;

class TermsAndConditions extends Component
{
    public $terms;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($terms)
    {
        $this->terms = $terms;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.terms-and-conditions');
    }
}