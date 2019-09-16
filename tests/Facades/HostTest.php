<?php

namespace Tests\Facades;

use Exception;
use Helldar\BlacklistServer\Facades\Helpers\Validator;
use Helldar\BlacklistServer\Facades\Host;
use Helldar\BlacklistServer\Models\Host as HostModel;
use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class HostTest extends TestCase
{
    protected $correct = 'http://example.com';

    protected $incorrect = 'example.com';

    protected $foo = 'foo';

    public function testStoreSuccess()
    {
        $item = Host::store($this->correct);

        $this->assertInstanceOf(HostModel::class, $item);

        $this->assertEquals($this->correct, $item->value);
    }

    public function testStoreFailValidationException()
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('The given data was invalid.');

        Host::store($this->foo);
    }

    public function testStoreFailSourceMessage()
    {
        try {
            Host::store($this->foo);
        } catch (Exception $exception) {
            $errors = Validator::flatten($exception->errors());

            $this->assertEquals('The value is not a valid URL.', Arr::first($errors));
        }
    }

    public function testStoreFailArgumentCount()
    {
        try {
            Host::store();
        } catch (Exception $exception) {
            $errors = Validator::flatten($exception->errors());

            $this->assertEquals('The value field is required.', Arr::first($errors));
        }
    }

    public function testDeleteSuccess()
    {
        Host::store($this->correct);

        $result = Host::delete($this->correct);

        $this->assertEquals(1, $result);
    }

    public function testDeleteFailValidationException()
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('The given data was invalid.');

        Host::delete($this->foo);
    }

    public function testDeleteFailValidationWithoutScheme()
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('The given data was invalid.');

        Host::delete($this->incorrect);
    }

    public function testDeleteFailSourceMessage()
    {
        try {
            Host::delete($this->foo);
        } catch (Exception $exception) {
            $errors = Validator::flatten($exception->errors());

            $this->assertEquals('The value is not a valid URL.', Arr::first($errors));
        }
    }

    public function testDeleteFailEmptySource()
    {
        try {
            Host::delete();
        } catch (Exception $exception) {
            $errors = Validator::flatten($exception->errors());

            $this->assertEquals('The value field is required.', Arr::first($errors));
        }
    }

    public function testExistsSuccess()
    {
        Host::store($this->correct);

        $result = Host::exists($this->correct);

        $this->assertEquals(true, $result);
    }

    public function testExistsFailValidationException()
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('The given data was invalid.');

        Host::exists($this->foo);
    }

    public function testExistsBlacklistDetected()
    {
        Host::store($this->correct);

        $result = Host::exists($this->correct);

        $this->assertEquals(true, $result);
    }

    public function testExistsFailSourceMessage()
    {
        try {
            Host::exists($this->foo);
        } catch (Exception $exception) {
            $errors = Validator::flatten($exception->errors());

            $this->assertEquals('The value is not a valid URL.', Arr::first($errors));
        }
    }

    public function testExistsFailEmptySource()
    {
        try {
            Host::exists();
        } catch (Exception $exception) {
            $errors = Validator::flatten($exception->errors());

            $this->assertEquals('The value field is required.', Arr::first($errors));
        }
    }
}
