<?php

namespace Tests\Facades;

use Exception;
use Helldar\BlacklistCore\Exceptions\BlacklistDetectedException;
use Helldar\BlacklistCore\Exceptions\UnknownTypeException;
use Helldar\BlacklistServer\Facades\Blacklist;
use Helldar\BlacklistServer\Facades\Validator;
use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class CheckTest extends TestCase
{
    protected $exists = 'foo@example.com';

    protected $incorrect = 'foo';

    protected $not_exists = 'bar@example.com';

    public function testSuccessExists()
    {
        $this->expectException(BlacklistDetectedException::class);
        $this->expectExceptionMessage("Checked {$this->exists} was found in our database.");

        Blacklist::store($this->exists, 'email');

        Blacklist::check($this->exists);
    }

    public function testSuccessNotExists()
    {
        $result = Blacklist::check($this->not_exists);

        $this->assertFalse($result);
    }

    public function testSelfBlockingUrl()
    {
        Blacklist::check('http://localhost');

        $this->assertTrue(true);
    }

    public function testSelfBlockingIp()
    {
        Blacklist::check('127.0.0.1');

        $this->assertTrue(true);
    }

    public function testFailValidationException()
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('The given data was invalid.');

        Blacklist::check(null);
    }

    public function testFailSourceMessage()
    {
        try {
            Blacklist::check($this->incorrect);
        } catch (Exception $exception) {
            $errors = Validator::flatten($exception);

            $this->assertEquals('The value must be at least 4 characters.', Arr::first($errors));
        }
    }

    public function testFailEmptySource()
    {
        $this->expectException(UnknownTypeException::class);
        $this->expectExceptionMessage('The type must be one of email, url, phone or ip, null given.');

        Blacklist::store();
    }
}
