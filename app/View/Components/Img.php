<?php

namespace App\View\Components;

use Illuminate\View\Component;
use App\Models\CompanyDetails;

class Img extends Component
{
    public $path;
    public $alt;
    public $class;
    public $width;
    public $height;
    public $style;
    public $loading;

    public function __construct($path = null, $alt = '', $class = '', $width = null, $height = null, $style = null, $loading = null)
    {
        $this->path = $path;
        $this->alt = $alt;
        $this->class = $class;
        $this->width = $width;
        $this->height = $height;
        $this->style = $style;
        $this->loading = $loading;
    }

    public function src()
    {
        $fallbackFile = optional(\App\Models\CompanyDetails::select('company_logo')->first())->company_logo ?? 'default.png';
        $fallbackPath = 'images/company/' . $fallbackFile;

        $fullPath = $this->path ? public_path($this->path) : null;

        if (!$fullPath || !is_file($fullPath)) {
            return asset($fallbackPath);
        }

        return asset($this->path);
    }

    public function render()
    {
        return view('components.img');
    }
}