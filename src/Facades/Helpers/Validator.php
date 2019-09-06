<?php

namespace Helldar\BlacklistServer\Facades\Helpers;

use Helldar\BlacklistServer\Helpers\ValidationHelper;
use Illuminate\Support\Facades\Facade;

class Validator extends Facade
{
    protected static function getFacadeAccessor()
    {
        return ValidationHelper::class;
    }
}
