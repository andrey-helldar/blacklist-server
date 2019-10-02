<?php

namespace Helldar\BlacklistServer\Facades;

use Helldar\BlacklistServer\Services\ValidationService;
use Illuminate\Contracts\Validation\Validator as ValidatorContract;
use Illuminate\Support\Facades\Facade;
use Illuminate\Validation\ValidationException;

/**
 * @method static ValidationService validate(array $data, bool $is_require_type = true)
 * @method static ValidatorContract make(array $data, bool $is_require_type = true)
 * @method static array flatten(ValidationException $exception)
 *
 * @return string
 */
class Validator extends Facade
{
    protected static function getFacadeAccessor()
    {
        return ValidationService::class;
    }
}
