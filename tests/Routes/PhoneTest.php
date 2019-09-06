<?php

namespace Tests\Routes;

use Helldar\SpammersServer\Facades\Phone;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class PhoneTest extends TestCase
{
    protected $correct = '123456789';

    protected $incorrect = 'foobarbaz123';

    protected $foo = '#-\\/`~';

    public function testStoreSuccess()
    {
        Phone::store($this->correct);

        $result = $this->call('POST', 'api/spammer', [
            'type'   => 'phone',
            'source' => $this->correct,
        ]);

        $result->assertStatus(200);
        $result->assertJsonStructure(['source', 'expired_at', 'created_at', 'updated_at']);
        $result->assertSee(\json_encode($this->correct));
    }

    public function testStoreFailValidation()
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('The given data was invalid.');

        Phone::store($this->foo);

        $this->call('POST', 'api/spammer', [
            'type'   => 'phone',
            'source' => $this->correct,
        ]);
    }

    public function testStoreFailSourceMessage()
    {
        $result = $this->call('POST', 'api/spammer', [
            'type'   => 'phone',
            'source' => $this->foo,
        ]);

        $result->assertStatus(400);

        $result->assertJsonStructure([
            'error' => ['code', 'msg'],
        ]);

        $result->assertSee('The source field is required.');
    }

    public function testStoreFailEmptySource()
    {
        $result = $this->call('POST', 'api/spammer', [
            'type' => 'phone',
        ]);

        $result->assertStatus(400);
        $result->assertJsonStructure(['error' => ['code', 'msg']]);
        $result->assertSee('The source field is required.');
    }

    public function testCheckIsDetected()
    {
        Phone::store($this->correct);

        $result = $this->call('GET', 'api/spammer', [
            'type'   => 'phone',
            'source' => $this->correct,
        ]);

        $phone = \json_encode($this->correct);
        $phone = \trim($phone, '"');

        $result->assertStatus(423);
        $result->assertJsonStructure(['error' => ['code', 'msg']]);
        $result->assertSee("Checked phone {$phone} was found in our database.");
    }

    public function testCheckNotDetected()
    {
        Phone::store($this->correct);

        $result = $this->call('GET', 'api/spammer', [
            'type'   => 'phone',
            'source' => '192.100.100.100',
        ]);

        $result->assertStatus(200);
        $result->assertSee('true');
    }

    public function testCheckFailValidation()
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('The given data was invalid.');

        Phone::store($this->foo);

        $this->call('GET', 'api/spammer', [
            'type'   => 'phone',
            'source' => $this->correct,
        ]);
    }

    public function testCheckFailSourceMessage()
    {
        $result = $this->call('GET', 'api/spammer', [
            'type'   => 'phone',
            'source' => $this->foo,
        ]);

        $result->assertStatus(400);
        $result->assertJsonStructure(['error' => ['code', 'msg']]);
        $result->assertSee('The source field is required.');
    }

    public function testCheckFailEmptySource()
    {
        $result = $this->call('GET', 'api/spammer', [
            'type' => 'phone',
        ]);

        $result->assertStatus(400);

        $result->assertJsonStructure([
            'error' => ['code', 'msg'],
        ]);

        $result->assertSee('The source field is required.');
    }
}