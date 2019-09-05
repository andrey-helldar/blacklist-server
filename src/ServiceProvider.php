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
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/config/settings.php', 'spammers_server');
    }
}
