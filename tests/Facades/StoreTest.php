<?php

namespace Tests\Facades;

use ArgumentCountError;
use Exception;
use Helldar\BlacklistCore\Exceptions\UnknownTypeException;
use Helldar\BlacklistCore\Facades\Validator;
use Helldar\BlacklistServer\Facades\Blacklist;
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
        $item = Blacklist::store([
            'type'  => 'email',
            'value' => $this->exists,
        ]);

        $this->assertInstanceOf(BlacklistModel::class, $item);

        $this->assertEquals($this->exists, $item->value);
    }

    public function testFailValidationException()
    {
        $this->expectException(UnknownTypeException::class);
        $this->expectExceptionMessage('The type must be one of email, url, phone or ip, null given.');

        Blacklist::store([
            'value' => $this->exists,
        ]);
    }

    public function testFailSourceMessage()
    {
        try {
            Blacklist::store([
                'type'  => 'email',
                'value' => $this->incorrect,
            ]);
        } catch (Exception $exception) {
            $errors = Validator::flatten($exception);

            $this->assertEquals('The value must be a valid email address.', Arr::first($errors));
        }
    }

    public function testFailEmptySource()
    {
        $this->expectException(ArgumentCountError::class);

        Blacklist::store();
    }

    public function testSelfBlockingIp()
    {
        try {
            Blacklist::store([
                'type'  => 'ip',
                'value' => '127.0.0.1',
            ]);
        } catch (Exception $exception) {
            $errors = Validator::flatten($exception);

            $this->assertEquals('You are trying to block yourself!', Arr::first($errors));
        }
    }

    public function testSelfBlockingUrl()
    {
        try {
            Blacklist::store([
                'type'  => 'url',
                'value' => 'http://localhost',
            ]);
        } catch (Exception $exception) {
            $errors = Validator::flatten($exception);

            $this->assertEquals('You are trying to block yourself!', Arr::first($errors));
        }
    }
}
