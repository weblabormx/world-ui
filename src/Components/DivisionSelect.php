<?php

namespace WeblaborMx\WorldUi\Components;

class DivisionSelect extends WorldComponent
{
    protected function endpoint(): string
    {
        return "/division/{$this->id}/children?fields=id,name";
    }

    public function __construct(
        public string|int|null $id = null
    ) {
        parent::__construct();
    }
}
