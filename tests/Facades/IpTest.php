<?php

namespace Tests\Facades;

use Exception;
use Helldar\SpammersServer\Facades\Helpers\Validator;
use Helldar\SpammersServer\Facades\Ip;
use Helldar\SpammersServer\Models\Ip as IpModel;
use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class IpTest extends TestCase
{
    protected $correct = '192.168.1.1';

    protected $incorrect = '192.168.256.3';

    protected $foo = '192.168';

    public function testStoreSuccess()
    {
        $item = Ip::store($this->correct);

        $this->assertInstanceOf(IpModel::class, $item);

        $this->assertEquals($this->correct, $item->source);
    }

    public function testStoreFailValidationException()
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('The given data was invalid.');

        Ip::store($this->foo);
    }

    public function testStoreFailSourceMessage()
    {
        try {
            Ip::store($this->foo);
        } catch (Exception $exception) {
            $errors = Validator::flatten($exception->errors());

            $this->assertEquals('The source must be a valid IP address.', Arr::first($errors));
        }
    }

    public function testStoreFailEmptySource()
    {
        try {
            Ip::store();
        } catch (Exception $exception) {
            $errors = Validator::flatten($exception->errors());

            $this->assertEquals('The source field is required.', Arr::first($errors));
        }
    }

    public function testDeleteSuccess()
    {
        Ip::store($this->correct);

        $result = Ip::delete($this->correct);

        $this->assertEquals(1, $result);
    }

    public function testDeleteFailValidationException()
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('The given data was invalid.');

        Ip::delete($this->foo);
    }

    public function testDeleteFailValidationWithoutScheme()
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('The given data was invalid.');

        Ip::delete($this->incorrect);
    }

    public function testDeleteFailSourceMessage()
    {
        try {
            Ip::delete($this->foo);
        } catch (Exception $exception) {
            $errors = Validator::flatten($exception->errors());

            $this->assertEquals('The source must be a valid IP address.', Arr::first($errors));
        }
    }

    public function testDeleteFailEmptySource()
    {
        try {
            Ip::delete();
        } catch (Exception $exception) {
            $errors = Validator::flatten($exception->errors());

            $this->assertEquals('The source field is required.', Arr::first($errors));
        }
    }

    public function testExistsSuccess()
    {
        Ip::store($this->correct);

        $result = Ip::exists($this->correct);

        $this->assertEquals(true, $result);
    }

    public function testExistsFailValidationException()
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('The given data was invalid.');

        Ip::exists($this->foo);
    }

    public function testExistsSpammerDetected()
    {
        Ip::store($this->correct);

        $result = Ip::exists($this->correct);

        $this->assertEquals(true, $result);
    }

    public function testExistsFailSourceMessage()
    {
        try {
            Ip::exists($this->foo);
        } catch (Exception $exception) {
            $errors = Validator::flatten($exception->errors());

            $this->assertEquals('The source must be a valid IP address.', Arr::first($errors));
        }
    }

    public function testExistsFailEmptySource()
    {
        try {
            Ip::exists();
        } catch (Exception $exception) {
            $errors = Validator::flatten($exception->errors());

            $this->assertEquals('The source field is required.', Arr::first($errors));
        }
    }
}
