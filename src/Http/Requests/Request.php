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
            'type'   => ['required', 'string', Rule::in(Rules::keysBasename())],
            'source' => Arr::get(Rules::get($type), $type, Rules::DEFAULT),
        ];
    }

    public function messages()
    {
        return Rules::MESSAGES;
    }
}
