<?php

namespace Helldar\SpammersServer\Facades;

use Helldar\SpammersServer\Services\Local\HostService as LocalService;
use Illuminate\Support\Facades\Facade;

class Host extends Facade
{
    /**
     * @method static \Helldar\SpammersServer\Models\Host store(string $source)
     * @method static integer delete(string $source)
     * @method static bool exists(string $source)
     */
    protected static function getFacadeAccessor()
    {
        return LocalService::class;
    }
}
