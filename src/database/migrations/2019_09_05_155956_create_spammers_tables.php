<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use function config;

class CreateSpammersTables extends Migration
{
    private $tables = [
        'spammers_ips'    => 'ipAddress',
        'spammers_emails' => 'string',
        'spammers_hosts'  => 'string',
        'spammers_phones' => 'string',
    ];

    private $ttl;

    public function __construct()
    {
        $this->ttl = (int) config('spammers_server.ttl', 7);
    }

    public function up()
    {
        foreach ($this->tables as $name => $type) {
            Schema::create($name, function (Blueprint $table) use ($name, $type) {
                $table->{$type}('source')->unique()->primary();

                $table->unsignedInteger('ttl')->default($this->ttl);

                $table->timestamp('expired_at');
                $table->timestamps();
                $table->softDeletes();
            });
        }
    }

    public function down()
    {
        foreach ($this->tables as $table) {
            Schema::dropIfExists($table);
        }
    }
}
