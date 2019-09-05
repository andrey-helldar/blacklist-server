<?php

namespace Helldar\SpammersServer\Facades;

use Helldar\SpammersServer\Services\Local\IpService;
use Illuminate\Support\Facades\Facade;

class Ip extends Facade
{
    protected static function getFacadeAccessor()
    {
        return IpService::class;
    }
}
