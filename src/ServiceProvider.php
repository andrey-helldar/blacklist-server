<?php

namespace Helldar\SpammersServer;

use Helldar\SpammersServer\Console\Delete;
use function config;
use function config_path;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    protected $defer = false;

    public function boot()
    {
        $this->publishes([
            __DIR__ . '/config/settings.php' => config_path('spammers_server.php'),
        ], 'config');

        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');

        $this->loadTranslationsFrom(__DIR__ . './resources/lang', 'spammers_server');

        if (config('spammers_server.use_routes', true)) {
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
        $this->mergeConfigFrom(__DIR__ . '/config/settings.php', 'spammers_server');
    }
}
