<?php

namespace Tests\Facades;

use Exception;
use Helldar\SpammersServer\Facades\Email;
use Helldar\SpammersServer\Facades\Helpers\Validator;
use Helldar\SpammersServer\Models\Email as EmailModel;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class EmailTest extends TestCase
{
    protected $correct = 'foo@example.com';

    protected $incorrect = 'bar@example.com';

    protected $foo = 'foo';

    public function testStoreSuccess()
    {
        $item = Email::store($this->correct);

        $this->assertInstanceOf(EmailModel::class, $item);

        $this->assertEquals($this->correct, $item->source);
    }

    public function testStoreFailValidationException()
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('The given data was invalid.');

        Email::store($this->foo);
    }

    public function testStoreFailSourceMessage()
    {
        try {
            Email::store($this->foo);
        } catch (Exception $exception) {
            $errors = Validator::flatten($exception->errors());

            $this->assertEquals('The source must be a valid email address.', Arr::first($errors));
        }
    }

    public function testStoreFailEmptySource()
    {
        try {
            Email::store();
        } catch (Exception $exception) {
            $errors = Validator::flatten($exception->errors());

            $this->assertEquals('The source field is required.', Arr::first($errors));
        }
    }

    public function testDeleteSuccess()
    {
        Email::store($this->correct);

        $result = Email::delete($this->correct);

        $this->assertEquals(1, $result);
    }

    public function testDeleteFailValidationException()
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('The given data was invalid.');

        Email::delete($this->foo);
    }

    public function testDeleteFailModelNotFound()
    {
        $this->expectException(ModelNotFoundException::class);

        Email::delete($this->incorrect);
    }

    public function testDeleteFailSourceMessage()
    {
        try {
            Email::delete($this->foo);
        } catch (Exception $exception) {
            $errors = Validator::flatten($exception->errors());

            $this->assertEquals('The source must be a valid email address.', Arr::first($errors));
        }
    }

    public function testDeleteFailArgumentCount()
    {
        try {
            Email::delete();
        } catch (Exception $exception) {
            $errors = Validator::flatten($exception->errors());

            $this->assertEquals('The source field is required.', Arr::first($errors));
        }
    }

    public function testExistsSuccess()
    {
        Email::store($this->correct);

        $resultTrue  = Email::exists($this->correct);
        $resultFalse = Email::exists($this->incorrect);

        $this->assertEquals(true, $resultTrue);
        $this->assertEquals(false, $resultFalse);
    }

    public function testExistsFailValidationException()
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('The given data was invalid.');

        Email::exists($this->foo);
    }

    public function testExistsSpammerDetected()
    {
        Email::store($this->correct);

        $result = Email::exists($this->correct);

        $this->assertEquals(true, $result);
    }

    public function testExistsFailSourceMessage()
    {
        try {
            Email::exists($this->foo);
        } catch (Exception $exception) {
            $errors = Validator::flatten($exception->errors());

            $this->assertEquals('The source must be a valid email address.', Arr::first($errors));
        }
    }

    public function testExistsFailArgumentCount()
    {
        try {
            Email::exists();
        } catch (Exception $exception) {
            $errors = Validator::flatten($exception->errors());

            $this->assertEquals('The source field is required.', Arr::first($errors));
        }
    }
}
