<?php

namespace Tests\Facades;

use Exception;
use Helldar\BlacklistCore\Exceptions\SelfBlockingException;
use Helldar\BlacklistCore\Exceptions\UnknownTypeException;
use Helldar\BlacklistServer\Facades\Blacklist;
use Helldar\BlacklistServer\Facades\Validator;
use Helldar\BlacklistServer\Models\Blacklist as BlacklistModel;
use Illuminate\Support\Arr;
use Tests\TestCase;

class StoreTest extends TestCase
{
    protected $exists = 'foo@example.com';

    protected $incorrect = 'foo';

    protected $not_exists = 'bar@example.com';

    public function testSuccess()
    {
        $item = Blacklist::store($this->exists, 'email');

        $this->assertInstanceOf(BlacklistModel::class, $item);

        $this->assertEquals($this->exists, $item->value);
    }

    public function testFailValidationException()
    {
        $this->expectException(UnknownTypeException::class);
        $this->expectExceptionMessage('The type must be one of email, url, phone or ip, null given.');

        Blacklist::store($this->exists);
    }

    public function testFailSourceMessage()
    {
        try {
            Blacklist::store($this->incorrect, 'email');
        }
        catch (Exception $exception) {
            $errors = Validator::flatten($exception);

            $this->assertEquals('The value must be a valid email address.', Arr::first($errors));
        }
    }

    public function testFailEmptySource()
    {
        $this->expectException(UnknownTypeException::class);
        $this->expectExceptionMessage('The type must be one of email, url, phone or ip, null given.');

        Blacklist::store();
    }

    public function testSelfBlockingIp()
    {
        try {
            Blacklist::store('127.0.0.1', 'ip');
        }
        catch (Exception $exception) {
            $errors = $exception instanceof SelfBlockingException
                ? $exception->getMessage()
                : Arr::first(Validator::flatten($exception));

            $this->assertEquals('You are trying to block yourself!', $errors);
        }
    }

    public function testSelfBlockingUrl()
    {
        try {
            Blacklist::store('http://localhost', 'url');
        }
        catch (Exception $exception) {
            $errors = $exception instanceof SelfBlockingException
                ? $exception->getMessage()
                : Arr::first(Validator::flatten($exception));

            $this->assertEquals('You are trying to block yourself!', $errors);
        }
    }

    public function testBlockResourcesStartingOnLocalhost()
    {
        $value = 'http://localhost.foo';

        $item = Blacklist::store($value, 'url');

        $this->assertInstanceOf(BlacklistModel::class, $item);

        $this->assertEquals($value, $item->value);
    }
}
