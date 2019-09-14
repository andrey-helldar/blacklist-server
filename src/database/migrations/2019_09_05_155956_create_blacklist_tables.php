<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBlacklistTables extends Migration
{
    private $tables = [
        'blacklist_ips'    => 'ipAddress',
        'blacklist_emails' => 'string',
        'blacklist_hosts'  => 'string',
        'blacklist_phones' => 'string',
    ];

    private $ttl;

    public function __construct()
    {
        $this->ttl = (int) config('blacklist_server.ttl', 7);
    }

    public function up()
    {
        foreach ($this->tables as $name => $type) {
            Schema::create($name, function (Blueprint $table) use ($name, $type) {
                $table->{$type}('value')->unique()->primary();

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
