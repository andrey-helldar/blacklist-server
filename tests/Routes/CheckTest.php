<?php

namespace Tests\Routes;

use Helldar\BlacklistCore\Constants\Server;
use Helldar\BlacklistServer\Facades\Blacklist;
use Tests\TestCase;

class CheckTest extends TestCase
{
    protected $exists = 'foo@example.com';

    protected $incorrect = 'foo';

    protected $not_exists = 'bar@example.com';

    protected $url = Server::URI;

    public function testSuccessExists()
    {
        Blacklist::store([
            'type'  => 'email',
            'value' => $this->exists,
        ]);

        $result = $this->call('GET', Server::URI . '/exists', [
            'value' => $this->exists,
        ]);

        $result->assertStatus(423);
        $result->assertJsonStructure(['error' => ['code', 'msg']]);
        $result->assertSee("Checked {$this->exists} was found in our database.");
    }

    public function testSuccessNotExists()
    {
        $result = $this->call('GET', $this->url, [
            'value' => $this->not_exists,
        ]);

        $result->assertStatus(200);
        $result->assertSee('ok');
    }

    public function testEmptySource()
    {
        $result = $this->call('GET', $this->url);

        $result->assertStatus(400);

        $result->assertJsonStructure(['error' => ['code', 'msg']]);

        $result->assertSee('The value field is required.');
    }
}
