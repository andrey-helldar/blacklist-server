<?php

namespace Tests\Facades;

use Exception;
use Helldar\BlacklistCore\Facades\Validator;
use Helldar\BlacklistServer\Facades\Blacklist;
use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

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

    public function testFailValidationException()
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('The given data was invalid.');

        Blacklist::exists([
            'value' => $this->exists,
        ]);
    }

    public function testFailSourceMessage()
    {
        try {
            Blacklist::exists([
                'value' => $this->incorrect,
            ]);
        }
        catch (Exception $exception) {
            $errors = Validator::flatten($exception->errors());

            $this->assertEquals('The value must be a valid email address.', Arr::first($errors));
        }
    }
}
