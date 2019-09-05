<?php

namespace Helldar\SpammersServer\Facades;

use Helldar\SpammersServer\Services\PhoneService;
use Illuminate\Support\Facades\Facade;

class Phone extends Facade
{
    protected static function getFacadeAccessor()
    {
        return PhoneService::class;
    }
}
