<?php

namespace WeblaborMx\WorldUi\Components;

use WeblaborMx\World\Entities\Division;

class CountrySelect extends WorldComponent
{
    protected int $cacheMinutes = 60;

    protected function options(): array
    {
        return Division::countries() ?? [];
    }
}
