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
            optionKeyValue: true,
            options: $this->getOptions()->mapWithKeys(fn ($v) => [$v['id'] => $v['name']])
        );
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

    protected function overwriteVariables(array $vars): void
    {
        foreach ($vars as $var => $value) {
            $this->{$var} = $value;
        }
    }

    protected function cacheKey(): string
    {
        return md5("worldui.native-select:{$this->endpoint()}");
    }
}
