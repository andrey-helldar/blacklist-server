<?php

namespace Helldar\BlacklistServer\Http\Requests;

use Helldar\BlacklistCore\Constants\Rules;
use Helldar\BlacklistCore\Constants\Types;
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
            'type' => ['required', 'string', Rule::in(Types::get())],
            'value' => Arr::get(Rules::get($type), $type, Rules::DEFAULT),
        ];
    }

    public function messages()
    {
        return Rules::MESSAGES;
    }
}
