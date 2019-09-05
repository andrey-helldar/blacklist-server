<?php

namespace Tests\Facades;

use Helldar\SpammersServer\Facades\Email;
use Tests\TestCase;

class EmailTest extends TestCase
{
    public function testStore()
    {
        $this->assertEquals('foo', Email::store('foo'));
    }
}
