<?php

namespace WeblaborMx\WorldUi\Components;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\ComponentAttributeBag;
use WeblaborMx\World\World;
use WireUi\View\Components\Select;

abstract class WorldComponent extends Select
{
    protected int $cacheMinutes = 1;
    public ?string $regex = null;

    abstract protected function endpoint(): string;

    public function __construct()
    {
        parent::__construct(
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

            // Override the props of the parent without constructor reassingment
            collect($data['attributes'])
                ->intersectByKeys($this->extractPublicProperties())
                ->except('attributes')
                ->each(function ($v, $k) {
                    $this->{$k} = $v;
                });

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
            function () {
                $data = collect(World::getClient()->makeSafeCall($this->endpoint()));

                if ($this->regex) {
                    $data = $data->map(function ($v) {
                        preg_match("/$this->regex/", $v['name'], $matches);
                        if ($matches) {
                            $v['name'] = $matches[0];
                        }

                        return $v;
                    })->filter(fn ($v) => !(is_null($v) || empty($v)));
                }

                return $data;
            }
        );

        if ($options->isEmpty()) {
            Cache::forget($key);
        }

        return $options;
    }

    protected function cacheKey(): string
    {
        return md5("worldui.native-select:{$this->endpoint()}{$this->regex}");
    }
}
