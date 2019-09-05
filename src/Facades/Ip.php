<?php

namespace Helldar\SpammersServer\Facades;

use Helldar\SpammersServer\Services\Local\IpService as LocalService;
use Illuminate\Support\Facades\Facade;

class Ip extends Facade
{
    /**
     * @method static \Helldar\SpammersServer\Models\Ip store(string $source)
     * @method static integer delete(string $source)
     * @method static bool exists(string $source)
     */
    protected static function getFacadeAccessor()
    {
        return LocalService::class;
    }
}
