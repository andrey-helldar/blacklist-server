<?php

namespace Helldar\BlacklistServer\Services;

use Helldar\BlacklistCore\Constants\Rules;
use Helldar\BlacklistCore\Constants\Types;
use Illuminate\Contracts\Validation\Validator as ValidatorContract;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class ValidationService
{
    /**
     * @param array $data
     * @param bool $is_require_type
     *
     * @throws \Helldar\BlacklistCore\Exceptions\UnknownTypeException
     */
    public function validate(array $data, bool $is_require_type = true)
    {
        $this->make($data, $is_require_type)
            ->validate();
    }

    /**
     * @param array $data
     * @param bool $is_require_type
     *
     * @return \Illuminate\Contracts\Validation\Validator
     * @throws \Helldar\BlacklistCore\Exceptions\UnknownTypeException
     *
     */
    public function make(array $data, bool $is_require_type = true): ValidatorContract
    {
        $type = Arr::get($data, 'type');

        return Validator::make($data, [
            'type'  => $this->getTypeRules($is_require_type),
            'value' => $this->getValueRules($type, $is_require_type),
        ], Rules::MESSAGES);
    }

    public function flatten(ValidationException $exception): array
    {
        return Arr::flatten($exception->errors());
    }

    private function getTypeRules(bool $is_require_type = true): array
    {
        return [
            $is_require_type ? 'required' : 'nullable',
            'string',
            Rule::in(Types::get()),
        ];
    }

    /**
     * @param string|null $type
     * @param bool $is_require_type
     *
     * @return array
     * @throws \Helldar\BlacklistCore\Exceptions\UnknownTypeException
     *
     */
    private function getValueRules(string $type = null, bool $is_require_type = true): array
    {
        return Rules::get($type, $is_require_type);
    }
}
