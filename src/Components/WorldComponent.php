<?php

namespace WeblaborMx\WorldUi\Components;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use ReflectionClass;
use ReflectionProperty;
use WeblaborMx\World\Entities\Division;
use WireUi\Attributes\Process;
use WireUi\Components\Select\Base as Select;

abstract class WorldComponent extends Select
{
    protected int $cacheMinutes = 1;

    /** @return Division[] */
    abstract protected function options(): array;

    public ?string $regex = null;
    /** @var callable|null */
    public mixed $formatUsing = null;
    /** @var callable|null */
    public mixed $filterUsing = null;

    #[Process()]
    protected function process(): void
    {
        $this->optionLabel = 'name';
        $this->optionValue = 'id';
        $this->options = $this->getOptions();

        parent::process();
    }

    private function getOptions(): Collection
    {
        $key = $this->cacheKey();

        /** @var Collection */
        $options = Cache::remember(
            $key,
            now()->addMinutes($this->cacheMinutes),
            function () {
                $data = collect($this->options())
                    ->sortBy('name');

                if ($this->filterUsing) {
                    $data = $data->filter($this->filterUsing);
                }

                if ($this->formatUsing) {
                    $data = $data->map($this->formatUsing);
                }

                if ($this->regex) {
                    $data = $data->map(function (Division $v) {
                        preg_match("/$this->regex/", $v->name, $matches);

                        if ($matches) {
                            $v->name = $matches[0];
                        }

                        return $v;
                    })->filter(
                        fn(Division $v) => !(is_null($v->name) || empty($v->name))
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

    /** @return string[] */
    protected function cacheParameters(): array
    {
        return [];
    }

    private function cacheKey(): string
    {
        $data = collect($this->getCacheParameters())
            ->map(function ($v) {
                if (isset($this->{$v})) {
                    return $this->{$v};
                }

                return $this->attributes->get($v, '');
            })
            ->filter(fn($v) => is_scalar($v))
            ->implode('|');

        $data = substr($data, 0, 128);

        return md5("worldui.native-select:{$data}|{$this->regex}");
    }

    /** @return string[] */
    private function getCacheParameters(): array
    {
        return array_merge(['regex', 'formatUsing', 'filterUsing'], $this->cacheParameters());
    }
}
