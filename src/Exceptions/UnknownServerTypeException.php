<?php

namespace Helldar\SpammersServer\Exceptions;

use Exception;
use Helldar\SpammersServer\Constants\Rules;
use function sprintf;

class UnknownServerTypeException extends Exception
{
    public function __construct($type)
    {
        $message = sprintf('The server must be one of the types: %s, "%s" given.', Rules::keysDivided(' or '), $type);

        parent::__construct($message, 500);
    }
}
