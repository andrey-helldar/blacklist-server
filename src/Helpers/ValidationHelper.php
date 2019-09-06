<?php

namespace Helldar\SpammersServer\Helpers;

use Helldar\SpammersServer\Constants\Rules;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use function compact;

class ValidationHelper
{
    public function validate(string $model, string $source = null)
    {
        $this
            ->make($model, $source)
            ->validate();
    }

    public function errors(string $model, string $source = null)
    {
        return $this
            ->make($model, $source)
            ->errors();
    }

    public function flatten(array $errors)
    {
        return Arr::flatten($errors);
    }

    public function make(string $model, string $source = null)
    {
        return Validator::make(compact('source'), [
            'source' => Rules::get($model),
        ], Rules::MESSAGES);
    }
}
