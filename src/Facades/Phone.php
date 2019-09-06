<?php

namespace Helldar\BlacklistServer\Facades;

use Helldar\BlacklistServer\Services\PhoneService;
use Illuminate\Support\Facades\Facade;

class Phone extends Facade
{
    protected static function getFacadeAccessor()
    {
        return PhoneService::class;
    }
}
