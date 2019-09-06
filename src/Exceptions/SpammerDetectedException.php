<?php

namespace Helldar\SpammersServer\Exceptions;

use Exception;
use Illuminate\Support\Str;
use function sprintf;

class SpammerDetectedException extends Exception
{
    public function __construct(string $type, string $source)
    {
        $message = sprintf('Checked %s %s was found in our database.', Str::lower($type), Str::lower($source));

        parent::__construct($message, 423);
    }
}
