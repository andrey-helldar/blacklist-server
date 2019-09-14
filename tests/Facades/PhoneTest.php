<?php

namespace Tests\Facades;

use Exception;
use Helldar\BlacklistServer\Facades\Helpers\Validator;
use Helldar\BlacklistServer\Facades\Phone;
use Helldar\BlacklistServer\Models\Phone as PhoneModel;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class PhoneTest extends TestCase
{
    protected $correct = '123456789';

    protected $incorrect = 'foobarbaz123';

    protected $foo = '#-\\/`~';

    public function testStoreSuccess()
    {
        $item = Phone::store($this->correct);

        $this->assertInstanceOf(PhoneModel::class, $item);

        $this->assertEquals($this->correct, $item->value);
    }

    public function testStoreFailValidationException()
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('The given data was invalid.');

        Phone::store($this->foo);
    }

    public function testStoreFailSourceMessage()
    {
        try {
            Phone::store($this->foo);
        } catch (Exception $exception) {
            $errors = Validator::flatten($exception->errors());

            $this->assertEquals('The value field is required.', Arr::first($errors));
        }
    }

    public function testStoreFailEmptySource()
    {
        try {
            Phone::store();
        } catch (Exception $exception) {
            $errors = Validator::flatten($exception->errors());

            $this->assertEquals('The value field is required.', Arr::first($errors));
        }
    }

    public function testDeleteSuccess()
    {
        Phone::store($this->correct);

        $result = Phone::delete($this->correct);

        $this->assertEquals(1, $result);
    }

    public function testDeleteFailValidationException()
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('The given data was invalid.');

        Phone::delete($this->foo);
    }

    public function testDeleteFailModelNotFound()
    {
        $this->expectException(ModelNotFoundException::class);

        Phone::delete($this->incorrect);
    }

    public function testDeleteFailSourceMessage()
    {
        try {
            Phone::delete($this->foo);
        } catch (Exception $exception) {
            $errors = Validator::flatten($exception->errors());

            $this->assertEquals('The value field is required.', Arr::first($errors));
        }
    }

    public function testDeleteFailEmptySource()
    {
        try {
            Phone::delete();
        } catch (Exception $exception) {
            $errors = Validator::flatten($exception->errors());

            $this->assertEquals('The value field is required.', Arr::first($errors));
        }
    }

    public function testExistsSuccess()
    {
        Phone::store($this->correct);

        $result = Phone::exists($this->correct);

        $this->assertEquals(true, $result);
    }

    public function testExistsFailValidationException()
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('The given data was invalid.');

        Phone::exists($this->foo);
    }

    public function testExistsBlacklistDetected()
    {
        Phone::store($this->correct);

        $result = Phone::exists($this->correct);

        $this->assertEquals(true, $result);
    }

    public function testExistsFailSourceMessage()
    {
        try {
            Phone::exists($this->foo);
        } catch (Exception $exception) {
            $errors = Validator::flatten($exception->errors());

            $this->assertEquals('The value field is required.', Arr::first($errors));
        }
    }

    public function testExistsFailEmptySource()
    {
        try {
            Phone::exists();
        } catch (Exception $exception) {
            $errors = Validator::flatten($exception->errors());

            $this->assertEquals('The value field is required.', Arr::first($errors));
        }
    }
}
