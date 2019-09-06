<?php

namespace Helldar\BlacklistServer\Facades;

use Helldar\BlacklistServer\Services\HostService;
use Illuminate\Support\Facades\Facade;

class Host extends Facade
{
    protected static function getFacadeAccessor()
    {
        return HostService::class;
    }
}
