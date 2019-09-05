<?php

namespace Helldar\SpammersServer\Facades;

use Helldar\SpammersServer\Services\Local\PhoneService as LocalService;
use Illuminate\Support\Facades\Facade;

class Phone extends Facade
{
    /**
     * @method static \Helldar\SpammersServer\Models\Phone store(string $source)
     * @method static integer delete(string $source)
     * @method static bool exists(string $source)
     */
    protected static function getFacadeAccessor()
    {
        return LocalService::class;
    }
}
