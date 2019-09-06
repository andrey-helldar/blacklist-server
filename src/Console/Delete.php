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
        $models = [
            Email::class,
            Host::class,
            Ip::class,
            Phone::class,
        ];

        foreach ($models as $model) {
            $this->delete($model);
        }
    }

    /**
     * @param string|\Illuminate\Database\Eloquent\Model $model
     */
    private function delete(string $model)
    {
        $model::where('expired_at', '<=', Carbon::now())
            ->delete();
    }
}
