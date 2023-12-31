<?php

namespace WeblaborMx\WorldUi\Components;

use WeblaborMx\World\Entities\Division;

class DivisionSearchSelect extends WorldComponent
{

    protected function options(): array
    {
        return Division::search(
            "$this->search",
            $this->parentId,
            ['id', 'name']
        ) ?? [];
    }

    public function __construct(
        public ?string $search = null,
        public string|int|null $parentId = null
    ) {
        parent::__construct();
    }
}
