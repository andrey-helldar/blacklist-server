<?php

namespace Helldar\BlacklistServer;

use Helldar\BlacklistServer\Console\Delete;
use function config;
use function config_path;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    protected $defer = false;

    public function boot()
    {
        $this->publishes([
            __DIR__ . '/config/settings.php' => config_path('blacklist_server.php'),
        ], 'config');

        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');

        $this->loadTranslationsFrom(__DIR__ . './resources/lang', 'blacklist_server');

        if (config('blacklist_server.use_routes', true)) {
            $this->loadRoutesFrom(__DIR__ . '/routes/api.php');
        }

        if ($this->app->runningInConsole()) {
            $this->commands([
                Delete::class,
            ]);
        }
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/config/settings.php', 'blacklist_server');
    }
}
