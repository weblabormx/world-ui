# World UI

World UI is a component library, based on [WireUI](https://github.com/wireui/wireui) for easy implementation with [Weblabor World API](https://world.weblabor.mx).

## Components

### Country Select

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