<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyBlacklistsTableAddSourceColumn extends Migration
{
    public function up()
    {
        Schema::table('blacklists', function (Blueprint $table) {
            $table->ipAddress('source')->nullable()->after('ttl');
        });
    }

    public function down()
    {
        Schema::table('blacklists', function (Blueprint $table) {
            $table->dropColumn('source');
        });
    }
}
