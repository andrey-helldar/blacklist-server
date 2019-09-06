<?php

namespace Tests\Routes;

use Helldar\SpammersServer\Facades\Ip;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class IpTest extends TestCase
{
    protected $correct = '192.168.1.1';

    protected $incorrect = '192.168.256.3';

    protected $foo = '192.168';

    public function testStoreSuccess()
    {
        Ip::store($this->correct);

        $result = $this->call('POST', 'api/spammer', [
            'type'   => 'ip',
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

        Ip::store($this->foo);

        $this->call('POST', 'api/spammer', [
            'type'   => 'ip',
            'source' => $this->correct,
        ]);
    }

    public function testStoreFailSourceMessage()
    {
        $result = $this->call('POST', 'api/spammer', [
            'type'   => 'ip',
            'source' => $this->foo,
        ]);

        $result->assertStatus(400);

        $result->assertJsonStructure([
            'error' => ['code', 'msg'],
        ]);

        $result->assertSee('The source must be a valid IP address.');
    }

    public function testStoreFailEmptySource()
    {
        $result = $this->call('POST', 'api/spammer', [
            'type' => 'ip',
        ]);

        $result->assertStatus(400);
        $result->assertJsonStructure(['error' => ['code', 'msg']]);
        $result->assertSee('The source field is required.');
    }

    public function testCheckIsDetected()
    {
        Ip::store($this->correct);

        $result = $this->call('GET', 'api/spammer', [
            'type'   => 'ip',
            'source' => $this->correct,
        ]);

        $ip = \json_encode($this->correct);
        $ip = \trim($ip, '"');

        $result->assertStatus(423);
        $result->assertJsonStructure(['error' => ['code', 'msg']]);
        $result->assertSee("Checked ip {$ip} was found in our database.");
    }

    public function testCheckNotDetected()
    {
        Ip::store($this->correct);

        $result = $this->call('GET', 'api/spammer', [
            'type'   => 'ip',
            'source' => '192.100.100.100',
        ]);

        $result->assertStatus(200);
        $result->assertSee('true');
    }

    public function testCheckValidationIncorrect()
    {
        Ip::store($this->correct);

        $result = $this->call('GET', 'api/spammer', [
            'type'   => 'ip',
            'source' => $this->incorrect,
        ]);

        $result->assertStatus(400);
        $result->assertJsonStructure(['error' => ['code', 'msg']]);
        $result->assertSee('The source must be a valid IP address.');
    }

    public function testCheckFailValidationFoo()
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('The given data was invalid.');

        Ip::store($this->foo);

        $this->call('GET', 'api/spammer', [
            'type'   => 'ip',
            'source' => $this->correct,
        ]);
    }

    public function testCheckFailSourceMessage()
    {
        $result = $this->call('GET', 'api/spammer', [
            'type'   => 'ip',
            'source' => $this->foo,
        ]);

        $result->assertStatus(400);
        $result->assertJsonStructure(['error' => ['code', 'msg']]);
        $result->assertSee('The source must be a valid IP address.');
    }

    public function testCheckFailEmptySource()
    {
        $result = $this->call('GET', 'api/spammer', [
            'type' => 'ip',
        ]);

        $result->assertStatus(400);

        $result->assertJsonStructure([
            'error' => ['code', 'msg'],
        ]);

        $result->assertSee('The source field is required.');
    }
}
