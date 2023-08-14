<?php

namespace WeblaborMx\WorldUi\Components;

use Illuminate\View\Component;

class CountrySelect extends Component
{
    public $api = '/countries?fields=id,name';

    public function render()
    {
        return view('worldui::components.worldui-select');
    }
}
