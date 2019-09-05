<?php

namespace Tests;

use Helldar\SpammersServer\ServiceProvider;

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

        $app['config']->set('spammers_server.connection', $this->database);
    }

    private function setRoutes($app)
    {
        $app['router']->post('api/spammer/store', 'Helldar\SpammersServer\Http\Controllers\IndexController@store');
        $app['router']->get('api/spammer/exists', 'Helldar\SpammersServer\Http\Controllers\IndexController@exists');
    }
}
