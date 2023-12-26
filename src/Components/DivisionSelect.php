<?php

namespace WeblaborMx\WorldUi\Components;

class DivisionSelect extends WorldComponent
{
    protected function endpoint(): string
    {
        return "/division/{$this->id}/children?fields=id,name";
    }

    public function __construct(
        public string|int|null $id = null,
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
