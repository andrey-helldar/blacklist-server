<?php

namespace Tests\Routes;

use Helldar\SpammersServer\Facades\Host;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class HostTest extends TestCase
{
    protected $correct = 'http://example.com';

    protected $incorrect = 'example.com';

    protected $foo = 'foo';

    public function testStoreSuccess()
    {
        Host::store($this->correct);

        $result = $this->call('POST', 'api/spammer', [
            'type'   => 'host',
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

        Host::store($this->foo);

        $this->call('POST', 'api/spammer', [
            'type'   => 'host',
            'source' => $this->correct,
        ]);
    }

    public function testStoreFailSourceMessage()
    {
        $result = $this->call('POST', 'api/spammer', [
            'type'   => 'host',
            'source' => $this->foo,
        ]);

        $result->assertStatus(400);

        $result->assertJsonStructure([
            'error' => ['code', 'msg'],
        ]);

        $result->assertSee('The source is not a valid URL.');
    }

    public function testStoreFailEmptySource()
    {
        $result = $this->call('POST', 'api/spammer', [
            'type' => 'host',
        ]);

        $result->assertStatus(400);
        $result->assertJsonStructure(['error' => ['code', 'msg']]);
        $result->assertSee('The source field is required.');
    }

    public function testCheckIsDetected()
    {
        Host::store($this->correct);

        $result = $this->call('GET', 'api/spammer', [
            'type'   => 'host',
            'source' => $this->correct,
        ]);

        $host = \json_encode($this->correct);
        $host = \trim($host, '"');

        $result->assertStatus(423);
        $result->assertJsonStructure(['error' => ['code', 'msg']]);
        $result->assertSee("Checked host {$host} was found in our database.");
    }

    public function testCheckNotDetected()
    {
        Host::store($this->correct);

        $result = $this->call('GET', 'api/spammer', [
            'type'   => 'host',
            'source' => 'http://foo.example.com',
        ]);

        $result->assertStatus(200);
        $result->assertSee('true');
    }

    public function testCheckValidationIncorrect()
    {
        Host::store($this->correct);

        $result = $this->call('GET', 'api/spammer', [
            'type'   => 'host',
            'source' => $this->incorrect,
        ]);

        $result->assertStatus(400);
        $result->assertJsonStructure(['error' => ['code', 'msg']]);
        $result->assertSee('The source is not a valid URL.');
    }

    public function testCheckFailValidationFoo()
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('The given data was invalid.');

        Host::store($this->foo);

        $this->call('GET', 'api/spammer', [
            'type'   => 'host',
            'source' => $this->correct,
        ]);
    }

    public function testCheckFailSourceMessage()
    {
        $result = $this->call('GET', 'api/spammer', [
            'type'   => 'host',
            'source' => $this->foo,
        ]);

        $result->assertStatus(400);
        $result->assertJsonStructure(['error' => ['code', 'msg']]);
        $result->assertSee('The source is not a valid URL.');
    }

    public function testCheckFailEmptySource()
    {
        $result = $this->call('GET', 'api/spammer', [
            'type' => 'host',
        ]);

        $result->assertStatus(400);

        $result->assertJsonStructure([
            'error' => ['code', 'msg'],
        ]);

        $result->assertSee('The source field is required.');
    }
}
