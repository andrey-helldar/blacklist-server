<?php

namespace Helldar\SpammersServer\Exceptions;

use Exception;
use Helldar\SpammersServer\Constants\Server;
use function sprintf;

class UnknownServerTypeException extends Exception
{
    public function __construct($server_type)
    {
        $message = sprintf('The server must be one of the types: %s, "%s" given.', Server::getCompiledTypes(' or '), $server_type);

        parent::__construct($message, 500);
    }
}
