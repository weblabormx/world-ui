<?php

namespace WeblaborMx\WorldUi\Components;

use WeblaborMx\World\Entities\Division;

class DivisionSelect extends WorldComponent
{
    protected function options(): array
    {
        return Division::getChildren(
            intval($this->attributes->get('id')),
            ['id', 'name']
        ) ?? [];
    }

    protected function cacheParameters(): array
    {
        return ['id'];
    }
}
