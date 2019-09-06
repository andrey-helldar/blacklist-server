<?php

namespace Tests\Routes;

use Helldar\BlacklistServer\Facades\Email;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class EmailTest extends TestCase
{
    protected $correct = 'foo@example.com';

    protected $incorrect = 'bar@example.com';

    protected $foo = 'foo';

    public function testStoreSuccess()
    {
        Email::store($this->correct);

        $result = $this->call('POST', 'api/blacklist', [
            'type'   => 'email',
            'source' => $this->correct,
        ]);

        $result->assertStatus(200);
        $result->assertJsonStructure(['source', 'expired_at', 'created_at', 'updated_at']);
        $result->assertSee($this->correct);
    }

    public function testStoreFailValidation()
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('The given data was invalid.');

        Email::store($this->foo);

        $this->call('POST', 'api/blacklist', [
            'type'   => 'email',
            'source' => $this->correct,
        ]);
    }

    public function testStoreFailSourceMessage()
    {
        $result = $this->call('POST', 'api/blacklist', [
            'type'   => 'email',
            'source' => $this->foo,
        ]);

        $result->assertStatus(400);

        $result->assertJsonStructure([
            'error' => ['code', 'msg'],
        ]);

        $result->assertSee('The source must be a valid email address.');
    }

    public function testStoreFailEmptySource()
    {
        $result = $this->call('POST', 'api/blacklist', [
            'type' => 'email',
        ]);

        $result->assertStatus(400);

        $result->assertJsonStructure([
            'error' => ['code', 'msg'],
        ]);

        $result->assertSee('The source field is required.');
    }

    public function testCheckIsDetected()
    {
        Email::store($this->correct);

        $result = $this->call('GET', 'api/blacklist', [
            'type'   => 'email',
            'source' => $this->correct,
        ]);

        $result->assertStatus(423);
        $result->assertJsonStructure(['error' => ['code', 'msg']]);
        $result->assertSee("Checked email {$this->correct} was found in our database.");
    }

    public function testCheckNotDetected()
    {
        Email::store($this->correct);

        $result = $this->call('GET', 'api/blacklist', [
            'type'   => 'email',
            'source' => $this->incorrect,
        ]);

        $result->assertStatus(200);
        $result->assertSee('true');
    }

    public function testCheckFailValidation()
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('The given data was invalid.');

        Email::store($this->foo);

        $this->call('GET', 'api/blacklist', [
            'type'   => 'email',
            'source' => $this->correct,
        ]);
    }

    public function testCheckFailSourceMessage()
    {
        $result = $this->call('GET', 'api/blacklist', [
            'type'   => 'email',
            'source' => $this->foo,
        ]);

        $result->assertStatus(400);

        $result->assertJsonStructure([
            'error' => ['code', 'msg'],
        ]);

        $result->assertSee('The source must be a valid email address.');
    }

    public function testCheckFailEmptySource()
    {
        $result = $this->call('GET', 'api/blacklist', [
            'type' => 'email',
        ]);

        $result->assertStatus(400);

        $result->assertJsonStructure([
            'error' => ['code', 'msg'],
        ]);

        $result->assertSee('The source field is required.');
    }
}
