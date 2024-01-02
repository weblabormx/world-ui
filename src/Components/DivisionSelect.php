<?php

namespace WeblaborMx\WorldUi\Components;

use WeblaborMx\World\Entities\Division;

class DivisionSelect extends WorldComponent
{
    protected function options(): array
    {
        return Division::getChildren(intval($this->id), ['id', 'name']) ?? [];
    }

    public function __construct(
        public string|int|null $id = null
    ) {
        parent::__construct();
    }
}
