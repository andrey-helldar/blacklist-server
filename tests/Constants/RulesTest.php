<?php

namespace Tests\Constants;

use Helldar\SpammersServer\Constants\Rules;
use Tests\TestCase;

class RulesTest extends TestCase
{
    public function testGet()
    {
        $this->assertEquals(['required', 'string', 'email', 'max:255'], Rules::get('email'));

        $this->assertEquals(['required', 'string', 'url', 'max:255'], Rules::get('host'));

        $this->assertEquals(['required', 'string', 'max:255'], Rules::get('phone'));

        $this->assertEquals(['required', 'ip'], Rules::get('ip'));

        $this->assertEquals(['required', 'string', 'max:255'], Rules::get('foo'));
    }

    public function testKeys()
    {
        $this->assertEquals(['email', 'host', 'phone', 'ip'], Rules::keys());
    }

    public function testKeysDivided()
    {
        $this->assertEquals('"email", "host", "phone", "ip"', Rules::keysDivided());

        $this->assertEquals('"email" or "host" or "phone" or "ip"', Rules::keysDivided(' or '));
    }
}
