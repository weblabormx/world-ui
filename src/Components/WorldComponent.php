<?php

namespace WeblaborMx\WorldUi\Components;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use WeblaborMx\World\Entities\Division;
use WireUi\View\Components\Select;

abstract class WorldComponent extends Select
{
    protected int $cacheMinutes = 1;
    public ?string $regex = null;

    /** @return Division[] */
    abstract protected function options(): array;

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
                $data = collect($this->options())
                    ->sortBy('name');

                if ($this->regex) {
                    $data = $data->map(function (Division $v) {
                        preg_match("/$this->regex/", $v->name, $matches);

                        if ($matches) {
                            $v->name = $matches[0];
                        }

                        return $v;
                    })->filter(
                        fn (Division $v) => !(is_null($v->name) || empty($v->name))
                    );
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
        $data = collect($this->extractConstructorParameters())
            ->map(fn ($v) => $this->{$v})
            ->filter(fn ($v) => is_scalar($v))
            ->implode('|');

        $data = substr($data, 0, 128);

        return md5("worldui.native-select:{$data}|{$this->regex}");
    }
}
