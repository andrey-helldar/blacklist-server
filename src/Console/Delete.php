<?php

namespace Helldar\BlacklistServer\Console;

use Carbon\Carbon;
use Exception;
use Helldar\BlacklistServer\Models\Email;
use Helldar\BlacklistServer\Models\Host;
use Helldar\BlacklistServer\Models\Ip;
use Helldar\BlacklistServer\Models\Phone;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;

class Delete extends Command
{
    protected $signature = 'blacklist:delete';

    protected $description = 'Soft delete expired records';

    /**
     * @throws Exception
     */
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
     * @param string|Model $model
     * @throws Exception
     */
    private function delete(string $model)
    {
        $model::where('expired_at', '<=', Carbon::now())
            ->delete();
    }
}
