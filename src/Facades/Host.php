<?php

namespace Helldar\SpammersServer\Facades;

use Helldar\SpammersServer\Services\Local\HostService;
use Illuminate\Support\Facades\Facade;

class Host extends Facade
{
    protected static function getFacadeAccessor()
    {
        return HostService::class;
    }
}
