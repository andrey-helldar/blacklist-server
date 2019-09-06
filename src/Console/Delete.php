<?php

namespace Helldar\BlacklistServer\Console;

use Carbon\Carbon;
use Helldar\BlacklistServer\Models\Email;
use Helldar\BlacklistServer\Models\Host;
use Helldar\BlacklistServer\Models\Ip;
use Helldar\BlacklistServer\Models\Phone;
use Illuminate\Console\Command;

class Delete extends Command
{
    protected $signature = 'blacklist:delete';

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
