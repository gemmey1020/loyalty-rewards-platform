<?php

namespace App\View\Components;

use Illuminate\View\Component;

class StatCard extends Component
{
    public $icon;

    public $count;

    public $label;

    public $url;

    public $level;

    public $imageType;

    public function __construct($icon = 'fa-user', $count = 0, $label = '', $url = null, $imageType = null)
    {
        $this->icon = $icon;
        $this->count = $count;
        $this->label = $label;
        $this->url = $url;
        $this->imageType = $imageType;
        $this->level = $this->getCardLevelClass($count);
    }

    private function getCardLevelClass($value)
    {
        $digits = strlen((string) intval($value));

        if ($digits <= 4) {
            return 'medium';
        } else {
            return 'ultra';
        }
    }

    public function render()
    {
        return view('components.stat-card');
    }
}
