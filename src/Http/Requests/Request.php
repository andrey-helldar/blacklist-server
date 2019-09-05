<?php

namespace Helldar\SpammersServer\Http\Requests;

use Helldar\SpammersServer\Constants\Rules;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;

class Request extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $type = $this->get('type');

        return [
            'type'   => ['required', 'string', Rule::in(Rules::keys())],
            'source' => Arr::get(Rules::get($type), $type, Rules::DEFAULT),
        ];
    }
}
