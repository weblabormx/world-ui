<?php

use WeblaborMx\WorldUi\Components;

return [
    'endpoint' => rtrim(config('services.weblabor.world.endpoint', env('WEBLABOR_WORLD_ENDPOINT', 'https://world.weblabor.mx/api')), '/'),

    'api_token' => config('services.weblabor.world.token', env('WEBLABOR_WORLD_TOKEN')),

    'components' => [
        [
            'class' => Components\CountrySelect::class,
            'alias' => 'country-select'
        ],
        [
            'class' => Components\DivisionSelect::class,
            'alias' => 'division-select'
        ],
        [
            'class' => Components\DivisionSearchSelect::class,
            'alias' => 'division-search'
        ],
    ]
];
