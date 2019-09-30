<?php

namespace Tests\Facades;

use ArgumentCountError;
use Exception;
use Helldar\BlacklistCore\Exceptions\BlacklistDetectedException;
use Helldar\BlacklistCore\Facades\Validator;
use Helldar\BlacklistServer\Facades\Blacklist;
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

        Blacklist::store([
            'type'  => 'email',
            'value' => $this->exists,
        ]);

        Blacklist::check($this->exists);
    }

    public function testSuccessNotExists()
    {
        $result = Blacklist::check($this->not_exists);

        $this->assertEquals(false, $result);
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
        }
        catch (Exception $exception) {
            $errors = Validator::flatten($exception);

            $this->assertEquals('The value must be at least 4 characters.', Arr::first($errors));
        }
    }

    public function testFailEmptySource()
    {
        $this->expectException(ArgumentCountError::class);

        Blacklist::store();
    }
}
