<?php

namespace Tests\Facades;

use Helldar\BlacklistServer\Facades\Blacklist;
use Tests\TestCase;
use TypeError;

class ExistsTest extends TestCase
{
    protected $exists = 'foo@example.com';

    protected $incorrect = 'foo';

    protected $not_exists = 'bar@example.com';

    public function testSuccess()
    {
        Blacklist::store($this->exists, 'email');

        $resultTrue  = Blacklist::exists($this->exists);
        $resultFalse = Blacklist::exists($this->not_exists);

        $this->assertEquals(true, $resultTrue);
        $this->assertEquals(false, $resultFalse);
    }

    public function testIncorrectArgument()
    {
        $this->expectException(TypeError::class);

        Blacklist::exists($this->incorrect);
    }

    public function testFailValidationException()
    {
        Blacklist::store($this->exists, 'email');

        $returnTrue  = Blacklist::exists($this->exists);
        $returnFalse = Blacklist::exists($this->not_exists);

        $this->assertEquals(true, $returnTrue);
        $this->assertEquals(false, $returnFalse);
    }
}
