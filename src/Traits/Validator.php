<?php

namespace Helldar\BlacklistServer\Traits;

use Helldar\BlacklistCore\Facades\Validator as Facade;

trait Validator
{
    protected function validate(array $data, bool $is_require_type = true)
    {
        return Facade::validate($data, $is_require_type);
    }
}
