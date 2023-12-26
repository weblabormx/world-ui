<?php

namespace WeblaborMx\WorldUi\Components;

class CountrySelect extends WorldComponent
{
    protected int $cacheMinutes = 60;

    protected function endpoint(): string
    {
        return "/countries?fields=id,name";
    }

    public function __construct(
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
