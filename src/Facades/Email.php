<?php

namespace Helldar\BlacklistServer\Facades;

use Helldar\BlacklistServer\Services\EmailService;
use Illuminate\Support\Facades\Facade;

class Email extends Facade
{
    protected static function getFacadeAccessor()
    {
        return EmailService::class;
    }
}
