<?php

namespace Helldar\BlacklistServer\Rules;

use Helldar\BlacklistCore\Helpers\Str;
use Illuminate\Contracts\Validation\Rule;

class ExceptBlocking implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     *
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $except = config('blacklist_server.except', []);

        foreach ($except as $item) {
            if (Str::is($item, $value)) {
                return false;
            }
        }

        return true;
    }

    public function message()
    {
        return 'An attempt was made to block an excluded resource!';
    }
}
