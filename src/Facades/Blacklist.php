<?php

namespace Helldar\BlacklistServer\Facades;

use Helldar\BlacklistServer\Services\BlacklistService;
use Illuminate\Support\Facades\Facade;

/**
 * @method \Helldar\BlacklistServer\Models\Blacklist store(string $value = null, string $type = null)
 * @method void check(string $value = null, string $type = null)
 * @method bool exists(string $value = null, string $type = null)
 */
class Blacklist extends Facade
{
    protected static function getFacadeAccessor()
    {
        return BlacklistService::class;
    }
}
