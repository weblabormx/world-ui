<?php

namespace WeblaborMx\WorldUi\Components;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use WeblaborMx\World\World;
use WireUi\View\Components\Select;

abstract class WorldComponent extends Select
{
    protected int $cacheMinutes = 1;

    abstract protected function endpoint(): string;

    public function __construct()
    {
        parent::__construct(
            options: $this->getOptions(),
            optionLabel: 'name',
            optionValue: 'id'
        );
    }

    protected function getView(): string
    {
        return 'worldui::components.select';
    }

    public function render(): \Closure
    {
        return function (array $data) {
            // Automatically inherit WireUI options
            $data['attributes']->setAttributes(
                collect($data)->filter(
                    fn ($v, $k) => !str_starts_with($k, '__') && $k !== 'attributes' && is_scalar($v)
                )->merge($data['attributes'])
                    ->toArray()
            );

            return parent::render()($data);
        };
    }

    public function getOptions(): Collection
    {
        $key = $this->cacheKey();

        /** @var Collection */
        $options = Cache::remember(
            $key,
            now()->addMinutes($this->cacheMinutes),
            fn () => collect(World::getClient()->makeSafeCall($this->endpoint()))
        );

        if ($options->isEmpty()) {
            Cache::forget($key);
        }

        return $options;
    }

    protected function cacheKey(): string
    {
        return md5("worldui.native-select:{$this->endpoint()}");
    }
}
