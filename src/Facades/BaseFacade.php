<?php

namespace Helldar\SpammersServer\Facades;

use Helldar\SpammersServer\Constants\Server;
use Illuminate\Support\Facades\Facade;
use function config;

abstract class BaseFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        $type = Server::getType();

        return "Helldar\\SpammersServer\\Services\\{$type}\\IpService::class";
    }
}
