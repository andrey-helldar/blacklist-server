<?php

namespace Tests\Facades;

use Exception;
use Helldar\BlacklistCore\Facades\Validator;
use Helldar\BlacklistServer\Facades\Blacklist;
use Helldar\BlacklistServer\Models\Blacklist as BlacklistModel;
use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationException;
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
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('The given data was invalid.');

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
        }
        catch (Exception $exception) {
            $errors = Validator::flatten($exception->errors());

            $this->assertEquals('The value must be a valid email address.', Arr::first($errors));
        }
    }

    public function testFailEmptySource()
    {
        try {
            Blacklist::store();
        }
        catch (Exception $exception) {
            $errors = Validator::flatten($exception->errors());

            $this->assertEquals('The value field is required.', Arr::first($errors));
        }
    }
}
