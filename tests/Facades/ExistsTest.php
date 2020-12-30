<?php

namespace Tests\Facades;

use Helldar\BlacklistServer\Facades\Blacklist;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

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

        $this->assertTrue($resultTrue);
        $this->assertFalse($resultFalse);
    }

    public function testIncorrectArgument()
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('The given data was invalid.');

        Blacklist::exists($this->incorrect, 'email');
    }

    public function testFailValidationException()
    {
        Blacklist::store($this->exists, 'email');

        $returnTrue  = Blacklist::exists($this->exists);
        $returnFalse = Blacklist::exists($this->not_exists);

        $this->assertTrue($returnTrue);
        $this->assertFalse($returnFalse);
    }
}
