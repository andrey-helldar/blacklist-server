<?php

namespace Helldar\SpammersServer\Facades;

use Helldar\SpammersServer\Services\Local\EmailService;

class Email extends BaseFacade
{
    protected static $services = [
        'local' => EmailService::class,
    ];
}
