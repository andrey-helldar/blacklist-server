<?php

namespace Helldar\SpammersServer\Facades;

use Helldar\SpammersServer\Services\EmailService;
use Illuminate\Support\Facades\Facade;

class Email extends Facade
{
    protected static function getFacadeAccessor()
    {
        return EmailService::class;
    }
}
