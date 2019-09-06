<?php

namespace Helldar\SpammersServer\Constants;

use Helldar\SpammersServer\Models\Email;
use Helldar\SpammersServer\Models\Host;
use Helldar\SpammersServer\Models\Ip;
use Helldar\SpammersServer\Models\Phone;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use function array_keys;
use function array_map;
use function array_values;
use function class_basename;
use function implode;
use function sprintf;

class Rules
{
    const AVAILABLE = [
        Email::class => ['required', 'string', 'email', 'max:255'],
        Host::class  => ['required', 'string', 'url', 'max:255'],
        Phone::class => ['required', 'string', 'max:255'],
        Ip::class    => ['required', 'ip'],
    ];

    const DEFAULT   = ['required', 'string', 'max:255'];

    const MESSAGES  = [
        'source.url' => 'The :attribute is not a valid URL.',
    ];

    public static function get(string $model)
    {
        if ($result = Arr::get(self::AVAILABLE, $model)) {
            return $result;
        } else {
            foreach (array_keys(self::AVAILABLE) as $key) {
                if (Str::lower(class_basename($key)) === $model) {
                    return self::get($key);
                }
            }
        }

        return self::DEFAULT;
    }

    public static function keys()
    {
        return array_keys(self::AVAILABLE);
    }

    public static function keysBasename(): array
    {
        return
            array_values(
                array_map(
                    function ($item) {
                        return Str::lower(
                            class_basename($item)
                        );
                    }, self::keys()
                )
            );
    }

    public static function keysDivided(string $divider = ', ')
    {
        return
            implode($divider,
                array_map(
                    function ($item) {
                        return sprintf('"%s"', $item);
                    }, self::keysBasename()
                )
            );
    }
}
