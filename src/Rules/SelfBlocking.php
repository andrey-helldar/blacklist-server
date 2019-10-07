<?php

namespace Helldar\BlacklistServer\Rules;

use Helldar\BlacklistCore\Constants\Server;
use Helldar\BlacklistCore\Helpers\Str;
use Illuminate\Contracts\Validation\Rule;

class SelfBlocking implements Rule
{
    public function passes($attribute, $value)
    {
        foreach (Server::selfValues() as $self_value) {
            if (Str::is($self_value, $value)) {
                return false;
            }
        }

        return true;
    }

    public function message()
    {
        return 'You are trying to block yourself!';
    }
}
