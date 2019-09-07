<?php

namespace Helldar\BlacklistServer\Helpers;

use Helldar\BlacklistCore\Constants\Rules;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use function compact;

class ValidationHelper
{
    public function validate(string $type, string $source = null)
    {
        $this
            ->make($type, $source)
            ->validate();
    }

    public function errors(string $type, string $source = null)
    {
        return $this
            ->make($type, $source)
            ->errors();
    }

    public function flatten(array $errors)
    {
        return Arr::flatten($errors);
    }

    public function make(string $type, string $source = null)
    {
        return Validator::make(compact('source'), [
            'source' => Rules::get($type),
        ], Rules::MESSAGES);
    }
}
