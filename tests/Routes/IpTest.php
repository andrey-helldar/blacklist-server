<?php

namespace Tests\Routes;

use Helldar\BlacklistCore\Constants\Server;
use Helldar\BlacklistServer\Facades\Ip;
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

        $result = $this->call('POST', Server::URI, [
            'type'   => 'ip',
            'value' => $this->correct,
        ]);

        $result->assertStatus(200);
        $result->assertJsonStructure(['value', 'expired_at', 'created_at', 'updated_at']);
        $result->assertSee(\json_encode($this->correct));
    }

    public function testStoreFailValidation()
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('The given data was invalid.');

        Ip::store($this->foo);

        $this->call('POST', Server::URI, [
            'type'   => 'ip',
            'value' => $this->correct,
        ]);
    }

    public function testStoreFailSourceMessage()
    {
        $result = $this->call('POST', Server::URI, [
            'type'   => 'ip',
            'value' => $this->foo,
        ]);

        $result->assertStatus(400);

        $result->assertJsonStructure([
            'error' => ['code', 'msg'],
        ]);

        $result->assertSee('The value must be a valid IP address.');
    }

    public function testStoreFailEmptySource()
    {
        $result = $this->call('POST', Server::URI, [
            'type' => 'ip',
        ]);

        $result->assertStatus(400);
        $result->assertJsonStructure(['error' => ['code', 'msg']]);
        $result->assertSee('The value field is required.');
    }

    public function testCheckIsDetected()
    {
        Ip::store($this->correct);

        $result = $this->call('GET', Server::URI, [
            'type'   => 'ip',
            'value' => $this->correct,
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

        $result = $this->call('GET', Server::URI, [
            'type'   => 'ip',
            'value' => '192.100.100.100',
        ]);

        $result->assertStatus(200);
        $result->assertSee('true');
    }

    public function testCheckValidationIncorrect()
    {
        Ip::store($this->correct);

        $result = $this->call('GET', Server::URI, [
            'type'   => 'ip',
            'value' => $this->incorrect,
        ]);

        $result->assertStatus(400);
        $result->assertJsonStructure(['error' => ['code', 'msg']]);
        $result->assertSee('The value must be a valid IP address.');
    }

    public function testCheckFailValidationFoo()
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('The given data was invalid.');

        Ip::store($this->foo);

        $this->call('GET', Server::URI, [
            'type'   => 'ip',
            'value' => $this->correct,
        ]);
    }

    public function testCheckFailSourceMessage()
    {
        $result = $this->call('GET', Server::URI, [
            'type'   => 'ip',
            'value' => $this->foo,
        ]);

        $result->assertStatus(400);
        $result->assertJsonStructure(['error' => ['code', 'msg']]);
        $result->assertSee('The value must be a valid IP address.');
    }

    public function testCheckFailEmptySource()
    {
        $result = $this->call('GET', Server::URI, [
            'type' => 'ip',
        ]);

        $result->assertStatus(400);

        $result->assertJsonStructure([
            'error' => ['code', 'msg'],
        ]);

        $result->assertSee('The value field is required.');
    }
}
