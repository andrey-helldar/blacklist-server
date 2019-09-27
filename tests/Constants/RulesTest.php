<?php

namespace Tests\Constants;

use Helldar\BlacklistCore\Constants\Rules;
use Helldar\BlacklistCore\Constants\Types;
use Tests\TestCase;

class RulesTest extends TestCase
{
    /**
     * @throws \Helldar\BlacklistCore\Exceptions\UnknownTypeException
     */
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
        $this->assertEquals(['email', 'host', 'phone', 'ip'], Types::get());
    }

    public function testKeysDivided()
    {
        $this->assertEquals('email, host, phone or ip', Types::getDivided());

        $this->assertEquals('email, host, phone, ip', Types::getDivided(', ', ', '));

        $this->assertEquals('email or host or phone or ip', Types::getDivided(' or '));
    }
}
