<?php

namespace Helldar\SpammersServer\Facades;

use Helldar\SpammersServer\Constants\Server;
use Helldar\SpammersServer\Exceptions\UnknownServiceException;
use Illuminate\Support\Facades\Facade;
use function array_key_exists;
use function get_class;

abstract class BaseFacade extends Facade
{
    protected static $services = [];

    /**
     * @throws \Helldar\SpammersServer\Exceptions\UnknownServerTypeException
     * @throws \Helldar\SpammersServer\Exceptions\UnknownServiceException
     * @return mixed|string
     */
    protected static function getFacadeAccessor()
    {
        $type = Server::getType();

        if (!array_key_exists($type, self::$services)) {
            throw new UnknownServiceException(get_class(self::class), $type);
        }

        return self::$services[$type];
    }
}
