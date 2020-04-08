<?php

namespace App\View\Components;

use Illuminate\Support\Collection;
use Illuminate\View\Component;

class Card extends Component
{
    public $courses;
    public $image;
    public $titlePrefix;
    public $link;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(Collection $courses, $image = null, $titlePrefix = null, $link = null)
    {
        $this->courses = $courses;
        $this->image = $image ?: '/images/0.jfif';
        $this->titlePrefix = $titlePrefix;
        $this->link = $link;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.card');
    }
}
