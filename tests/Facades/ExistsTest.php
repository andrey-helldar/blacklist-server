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
        Blacklist::store([
            'type'  => 'email',
            'value' => $this->exists,
        ]);

        $resultTrue  = Blacklist::exists($this->exists);
        $resultFalse = Blacklist::exists($this->not_exists);

        $this->assertEquals(true, $resultTrue);
        $this->assertEquals(false, $resultFalse);
    }

    public function testIncorrectArgument()
    {
        $this->expectException(TypeError::class);

        Blacklist::exists([
            'value' => $this->incorrect,
        ]);
    }

    public function testFailValidationException()
    {
        Blacklist::store([
            'type'  => 'email',
            'value' => $this->exists,
        ]);

        $returnTrue  = Blacklist::exists($this->exists);
        $returnFalse = Blacklist::exists($this->not_exists);

        $this->assertEquals(true, $returnTrue);
        $this->assertEquals(false, $returnFalse);
    }
}
