<?php

namespace Helldar\SpammersServer\Facades;

use Helldar\SpammersServer\Services\Local\PhoneService;

class Phone extends BaseFacade
{
    protected static $services = [
        'local' => PhoneService::class,
    ];
}
