<?php

namespace WeblaborMx\WorldUi\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\View\Compilers\BladeCompiler;
use WeblaborMx\World\World;

class WorldUiServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->loadConfigs();
        $this->loadComponents();
    }

    public function boot()
    {
        if (!config('worldui.api_token')) return;

        World::setApiBase(config('worldui.endpoint'));
        World::init(config('worldui.api_token'));
    }

    protected function loadConfigs()
    {
        $root = __DIR__ . '/../..';

        $this->publishes([
            "{$root}/config/worldui.php" => config_path('worldui.php')
        ], 'worldui.config');

        $this->loadViewsFrom("{$root}/resources/views", 'worldui');
        $this->loadTranslationsFrom("{$root}/lang", 'worldui');
        $this->mergeConfigFrom("{$root}/config/worldui.php", 'worldui');
    }

    protected function loadComponents()
    {
        $this->callAfterResolving(BladeCompiler::class, static function (BladeCompiler $blade): void {
            foreach (config('worldui.components') as $component) {
                $blade->component($component['class'], $component['alias']);
            }
        });
    }
}
