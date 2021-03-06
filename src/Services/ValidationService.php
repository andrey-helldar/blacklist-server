<?php

namespace Helldar\BlacklistServer\Services;

use function filter_var;
use Helldar\BlacklistCore\Constants\Rules;
use Helldar\BlacklistCore\Constants\Server;
use Helldar\BlacklistCore\Constants\Types;
use Helldar\BlacklistCore\Exceptions\ExcludedBlockingDetectedException;
use Helldar\BlacklistCore\Exceptions\SelfBlockingException;
use Helldar\BlacklistCore\Helpers\Str;
use Illuminate\Contracts\Validation\Validator as ValidatorContract;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

use Illuminate\Validation\ValidationException;
use function parse_url;

class ValidationService
{
    /**
     * @param array $data
     * @param bool $is_require_type
     * @param bool $check_self_blocking
     *
     * @throws ExcludedBlockingDetectedException
     * @throws SelfBlockingException
     * @throws \Helldar\BlacklistCore\Exceptions\UnknownTypeException
     */
    public function validate(array $data, bool $is_require_type = true, bool $check_self_blocking = false)
    {
        $value = Arr::get($data, 'value');

        if ($check_self_blocking) {
            $this->validateSelfBlocking($value);
            $this->validateExcludedBlocking($value);
        }

        $this->make($data, $is_require_type)
            ->validate();
    }

    /**
     * @param array $data
     * @param bool $is_require_type
     *
     * @throws \Helldar\BlacklistCore\Exceptions\UnknownTypeException
     *
     * @return \Illuminate\Contracts\Validation\Validator
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
     * @throws \Helldar\BlacklistCore\Exceptions\UnknownTypeException
     *
     * @return array
     */
    private function getValueRules(string $type = null, bool $is_require_type = true): array
    {
        return Rules::get($type, $is_require_type);
    }

    /**
     * @param string|null $value
     *
     * @throws SelfBlockingException
     */
    private function validateSelfBlocking(string $value = null)
    {
        $value = $this->parse($value);

        foreach (Server::selfValues() as $self_value) {
            if (Str::is($self_value, $value)) {
                throw new SelfBlockingException();
            }
        }
    }

    /**
     * @param string|null $value
     *
     * @throws ExcludedBlockingDetectedException
     */
    private function validateExcludedBlocking(string $value = null)
    {
        $except = config('blacklist_server.except', []);

        $value = $this->parse($value);

        foreach ($except as $item) {
            if (Str::is($item, $value)) {
                throw new ExcludedBlockingDetectedException();
            }
        }
    }

    private function isUrl(string $value = null): bool
    {
        return filter_var($value, FILTER_VALIDATE_URL) !== false;
    }

    private function parse(string $value = null): ?string
    {
        return $this->isUrl($value)
            ? parse_url($value, PHP_URL_HOST)
            : $value;
    }
}
