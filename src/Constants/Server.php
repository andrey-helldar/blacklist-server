<?php

namespace Helldar\SpammersServer\Constants;

use Helldar\SpammersServer\Exceptions\UnknownServerTypeException;
use Illuminate\Support\Str;
use function array_map;
use function config;
use function implode;
use function in_array;
use function sprintf;

class Server
{
    const AVAILABLE_TYPES = ['local'];

    public static function getCompiledTypes(string $divider = ', ')
    {
        return implode($divider, array_map(function ($item) {
            return sprintf('"%s"', $item);
        }, self::AVAILABLE_TYPES));
    }

    /**
     * @throws \Helldar\SpammersServer\Exceptions\UnknownServerTypeException
     * @return string
     */
    public static function getType(): string
    {
        $type = Str::lower(config('spammers_server.type', 'remote'));

        if (!in_array($type, self::AVAILABLE_TYPES)) {
            throw new UnknownServerTypeException($type);
        }

        return $type;
    }
}
