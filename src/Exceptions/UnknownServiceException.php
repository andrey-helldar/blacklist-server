<?php

namespace Helldar\SpammersServer\Exceptions;

use Exception;
use function sprintf;

class UnknownServiceException extends Exception
{
    public function __construct($service, $type)
    {
        $message = sprintf('Class %s does not specify a value for server type %s.', $service, $type);

        parent::__construct($message, 500);
    }
}
