<?php

namespace Helldar\SpammersServer;

use function config;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    protected $defer = false;

    public function boot()
    {
        $this->publishes([
            __DIR__ . '/config/settings.php' => \config_path('spammers_server.php'),
        ], 'config');

        if (config('spammers_server.type') !== 'remote') {
            $this->loadMigrationsFrom(__DIR__ . '/database/migrations');
        }
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/config/settings.php', 'spammers_server');
    }
}
