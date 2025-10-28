<?php

namespace WeblaborMx\WorldUi\Components;

use WeblaborMx\World\Entities\Division;

class DivisionSearchSelect extends WorldComponent
{
    protected function options(): array
    {
        $search = $this->attributes->get('search', '');
        $parentId = $this->attributes->get('parentId');

        return Division::search(
            $search,
            is_null($parentId) ? null : intval($parentId),
            ['id', 'name']
        ) ?? [];
    }

    protected function cacheParameters(): array
    {
        return ['search', 'parentId'];
    }
}
