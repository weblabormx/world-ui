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
        public bool $clearable = true,
        public bool $searchable = true,
        public bool $multiselect = false,
        public bool $withoutItemsCount = false,
        public string $rightIcon = 'selector',
        public ?string $icon = null,
        public ?string $label = null,
        public ?string $hint = null,
        public ?string $placeholder = null,
        public bool $hideEmptyMessage = false,
    ) {
        parent::__construct();
        $this->overwriteVariables(get_defined_vars());
    }
}
