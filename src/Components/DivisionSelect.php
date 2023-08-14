<?php

namespace WeblaborMx\WorldUi\Components;

use Illuminate\View\Component;

class DivisionSelect extends Component
{
    public $api;

    public function __construct(
        string|int|null $id = null
    ) {
        if ($id) {
            $this->api = "/division/{$id}/children?fields=id,name";
        }
    }

    public function render()
    {
        return view('worldui::components.worldui-select');
    }
}
