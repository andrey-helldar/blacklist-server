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
        $this->assertEquals(['required', 'string', 'email', 'min:7', 'max:255'], Rules::get('email'));

        $this->assertEquals(['required', 'string', 'url', 'min:5', 'max:255'], Rules::get('url'));

        $this->assertEquals(['required', 'string', 'min:4', 'max:255'], Rules::get('phone'));

        $this->assertEquals(['required', 'string', 'ip'], Rules::get('ip'));

        $this->assertEquals(['required', 'string', 'min:4', 'max:255'], Rules::get('foo'));
    }

    public function testKeys()
    {
        $this->assertEquals(['email', 'url', 'phone', 'ip'], Types::get());
    }

    public function testKeysDivided()
    {
        $this->assertEquals('email, url, phone or ip', Types::getDivided());

        $this->assertEquals('email, url, phone, ip', Types::getDivided(', ', ', '));

        $this->assertEquals('email or url or phone or ip', Types::getDivided(' or '));
    }
}
