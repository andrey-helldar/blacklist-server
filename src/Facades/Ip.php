<?php

namespace Helldar\BlacklistServer\Facades;

use Helldar\BlacklistServer\Services\IpService;
use Illuminate\Support\Facades\Facade;

class Ip extends Facade
{
    protected static function getFacadeAccessor()
    {
        return IpService::class;
    }
}
