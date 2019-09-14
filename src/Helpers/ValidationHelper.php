<?php

namespace Helldar\BlacklistServer\Helpers;

use Helldar\BlacklistCore\Constants\Rules;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use function compact;

class ValidationHelper
{
    public function validate(string $type, string $value = null)
    {
        $this
            ->make($type, $value)
            ->validate();
    }

    public function errors(string $type, string $value = null)
    {
        return $this
            ->make($type, $value)
            ->errors();
    }

    public function flatten(array $errors)
    {
        return Arr::flatten($errors);
    }

    public function make(string $type, string $value = null)
    {
        return Validator::make(compact('value'), [
            'value' => Rules::get($type),
        ], Rules::MESSAGES);
    }
}
