<?php

namespace Helldar\SpammersServer\Facades;

use Helldar\SpammersServer\Services\Local\HostService;

class Host extends BaseFacade
{
    protected static $services = [
        'local' => HostService::class,
    ];
}
