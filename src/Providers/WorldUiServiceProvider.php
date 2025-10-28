<?php

namespace WeblaborMx\WorldUi\Providers;

use Illuminate\Support\Facades\Config;
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

        $this->loadTranslationsFrom("{$root}/lang", 'worldui');
        $this->mergeConfigFrom("{$root}/config/worldui.php", 'worldui');
    }

    protected function loadComponents()
    {
        $wireUiConfig = Config::get('wireui');
        $components = [...$wireUiConfig['components']];

        foreach (Config::get('worldui.components') as $component) {
            Config::set('wireui.' . $component['alias'], $wireUiConfig['select']);

            $components[$component['alias']] = $component;
        }

        Config::set('wireui.components', $components);
    }
}
