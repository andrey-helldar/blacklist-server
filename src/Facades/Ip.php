<?php

namespace Helldar\SpammersServer\Facades;

use Helldar\SpammersServer\Services\Local\IpService;

class Ip extends BaseFacade
{
    protected static $services = [
        'local' => IpService::class,
    ];
}
