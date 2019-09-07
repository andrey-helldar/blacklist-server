<?php

namespace Tests;

use Helldar\BlacklistCore\Constants\Server;
use Helldar\BlacklistServer\ServiceProvider;

abstract class TestCase extends \Orchestra\Testbench\TestCase
{
    private $database = 'testing';

    protected function setUp(): void
    {
        parent::setUp();

        $this->loadLaravelMigrations($this->database);

        $this->artisan('migrate', ['--database' => $this->database])->run();
    }

    protected function getEnvironmentSetUp($app)
    {
        $this->setDatabase($app);
        $this->setRoutes($app);
    }

    protected function getPackageProviders($app)
    {
        return [ServiceProvider::class];
    }

    private function setDatabase($app)
    {
        $app['config']->set('database.default', $this->database);

        $app['config']->set('database.connections.' . $this->database, [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);

        $app['config']->set('blacklist_server.connection', $this->database);
    }

    private function setRoutes($app)
    {
        $app['router']->post(Server::URI, 'Helldar\BlacklistServer\Http\Controllers\IndexController@store');
        $app['router']->get(Server::URI, 'Helldar\BlacklistServer\Http\Controllers\IndexController@check');
    }
}
