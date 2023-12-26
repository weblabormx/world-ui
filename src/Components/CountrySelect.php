<?php

namespace WeblaborMx\WorldUi\Components;

class CountrySelect extends WorldComponent
{
    protected int $cacheMinutes = 60;

    protected function endpoint(): string
    {
        return "/countries?fields=id,name";
    }
}
