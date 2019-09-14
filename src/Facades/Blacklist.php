<?php

namespace Helldar\BlacklistServer\Facades;

use Helldar\BlacklistServer\Services\BlacklistService;
use Illuminate\Support\Facades\Facade;

class Blacklist extends Facade
{
    protected static function getFacadeAccessor()
    {
        return BlacklistService::class;
    }
}
