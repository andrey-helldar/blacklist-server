<?php

namespace Helldar\SpammersServer\Console;

use Carbon\Carbon;
use Helldar\SpammersServer\Models\Email;
use Helldar\SpammersServer\Models\Host;
use Helldar\SpammersServer\Models\Ip;
use Helldar\SpammersServer\Models\Phone;
use Illuminate\Console\Command;

class Delete extends Command
{
    protected $signature = 'spammers:delete';

    protected $description = 'Soft delete expired records';

    public function handle()
    {
        Email::where('expired_at', '<=', Carbon::now())->delete();

        Host::where('expired_at', '<=', Carbon::now())->delete();

        Ip::where('expired_at', '<=', Carbon::now())->delete();

        Phone::where('expired_at', '<=', Carbon::now())->delete();
    }
}
