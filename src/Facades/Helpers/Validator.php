<?php

namespace Helldar\SpammersServer\Facades\Helpers;

use Helldar\SpammersServer\Helpers\ValidationHelper;
use Illuminate\Support\Facades\Facade;

class Validator extends Facade
{
    protected static function getFacadeAccessor()
    {
        return ValidationHelper::class;
    }
}
