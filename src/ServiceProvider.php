<?php

namespace Helldar\SpammersServer;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    protected $defer = false;

    public function boot()
    {
        $this->publishes([
            __DIR__ . '/config/settings.php' => \config_path('spammers_server.php'),
        ], 'config');

        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');

        $this->loadRoutesFrom(__DIR__ . '/routes/api.php');
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/config/settings.php', 'spammers_server');
    }
}
