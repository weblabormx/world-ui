# World UI

World UI is a component library, based on [WireUI](https://github.com/wireui/wireui) for easy implementation with [Weblabor World API](https://world.weblabor.mx).

## Install

Install the package through composer using:

```
composer require weblabormx/world-ui
```

Then, configure on your `config/services.php` your World API Token.

```php
[
    //...
    'weblabor' => [
        'world' => [
            'token' => env('WEBLABOR_WORLD_TOKEN'),
            // 'endpoint' => env('WEBLABOR_WORLD_ENDPOINT') // OPTIONAL
        ]
    ]
];
```

### Publishing assets

You can optionally publish the `wireui.php` config file using:

```
php artisan vendor:publish --tag='worldui.config'
```

**Default config:**

```php
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
```

---

## Components

### Country Select

![](https://github.com/weblabormx/world-ui/assets/46875694/516a4fdd-150c-4a5b-a4fa-19d7faafafb5)


[Check all fields](https://livewire-wireui.com/docs/select#select-options)

**Example:**

```php
<x-country-select
    wire:model="country"
    wire:key="country-select"
    label="Country"
    placeholder="Your country" />
```

### Division Select

![](https://github.com/weblabormx/world-ui/assets/46875694/a5307d2f-5377-4a22-bd96-86cff3ee6907)


**Unique Fields**
- id: The ID of the division to get the children from

[Check all fields](https://livewire-wireui.com/docs/select#select-options)

**Example:**

```php
<x-division-select
    label="State"
    id="{{ $country }}"
    wire:key="state-select"
    placeholder="Your state" />
```

### Search Select

![](https://github.com/weblabormx/world-ui/assets/46875694/5b982042-5734-4951-9440-f1ed06ee87d8)


**Unique Fields**
- search: Content to be searched
- parentId: Division ID to filter the results

[Check all fields](https://livewire-wireui.com/docs/native-select#native-select-options)

**Example:**

```php
<x-input label="Search"
    wire:model.lazy="search"
    placeholder="Your search" />

<x-division-search
    search="{{ $search }}"
    parentId="{{ $country }}"
    wire:key="search-select"
    wire:target="search"
    wire:loading.remove />
```
