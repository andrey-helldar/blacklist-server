<?php

namespace Helldar\SpammersServer\Constants;

use Illuminate\Support\Arr;
use function array_keys;
use function array_map;
use function implode;
use function sprintf;

class Rules
{
    const AVAILABLE = [
        'email' => ['required', 'string', 'email', 'max:255'],
        'host'  => ['required', 'string', 'url', 'max:255'],
        'phone' => ['required', 'string', 'max:255'],
        'ip'    => ['required', 'ip'],
    ];

    const DEFAULT   = ['required', 'string', 'max:255'];

    public static function get(string $key)
    {
        return Arr::get(self::AVAILABLE, $key, self::DEFAULT);
    }

    public static function keys()
    {
        return array_keys(self::AVAILABLE);
    }

    public static function keysDivided(string $divider = ', ')
    {
        return implode($divider,
            array_map(
                function ($item) {
                    return sprintf('"%s"', $item);
                }, self::keys()
            )
        );
    }
}
