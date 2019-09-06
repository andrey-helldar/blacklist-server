<?php

namespace Helldar\SpammersServer\Services;

use Helldar\SpammersServer\Models\Email;

class EmailService extends BaseService
{
    protected $model = Email::class;
}
