<?php

namespace Tests\Routes;

use Helldar\BlacklistCore\Constants\Server;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class StoreTest extends TestCase
{
    protected $exists = 'foo@example.com';

    protected $incorrect = 'foo';

    protected $not_exists = 'bar@example.com';

    public function testSuccess()
    {
        $result = $this->call('POST', Server::URI, [
            'type'  => 'email',
            'value' => $this->exists,
        ]);

        $result->assertStatus(200);
        $result->assertJsonStructure(['value', 'expired_at', 'created_at', 'updated_at']);
        $result->assertSee($this->exists);
    }

    public function testFailValidation()
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('The given data was invalid.');

        $this->call('POST', Server::URI, [
            'type'  => 'email',
            'value' => $this->incorrect,
        ]);
    }

    public function testFailSourceMessage()
    {
        $result = $this->call('POST', Server::URI, [
            'type'  => 'email',
            'value' => $this->incorrect,
        ]);

        $result->assertStatus(400);

        $result->assertJsonStructure([
            'error' => ['code', 'msg'],
        ]);

        $result->assertSee('The value must be a valid email address.');
    }

    public function testFailEmptySource()
    {
        $result = $this->call('POST', Server::URI);

        $result->assertStatus(400);

        $result->assertJsonStructure([
            'error' => ['code', 'msg'],
        ]);

        $result->assertSee('The value field is required.');
    }
}
