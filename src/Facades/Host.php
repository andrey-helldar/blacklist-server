<?php

namespace Helldar\SpammersServer\Facades;

use Helldar\SpammersServer\Services\HostService;
use Illuminate\Support\Facades\Facade;

class Host extends Facade
{
    protected static function getFacadeAccessor()
    {
        return HostService::class;
    }
}
