<?php

namespace WeblaborMx\WorldUi\Components;


class DivisionSearchSelect extends WorldComponent
{
    protected function endpoint(): string
    {
        return "/search/{$this->search}/{$this->parentId}?fields=id,name";
    }

    public function __construct(
        public ?string $search = null,
        public string|int|null $parentId = null,
        ?string $regex = null
    ) {
        parent::__construct(regex: $regex);
    }
}
