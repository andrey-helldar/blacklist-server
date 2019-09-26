<?php

namespace Helldar\BlacklistServer\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

use function array_merge;
use function config;
use function request;
use function trim;

class Blacklist extends Model
{
    public $incrementing = false;

    protected $casts = [
        'ttl' => 'integer',
    ];

    protected $dates = ['expired_at'];

    protected $fillable = ['value', 'type', 'ttl', 'source', 'expired_at'];

    protected $hidden = ['ttl', 'source'];

    protected $keyType = 'string';

    protected $primaryKey = 'value';

    public function __construct(array $attributes = [])
    {
        $this->setConnection(config('blacklist_server.connection', 'mysql'));

        parent::__construct(array_merge($attributes, [
            'source' => request()->getClientIp() ?? '127.0.0.1',
        ]));
    }

    protected function setTypeAttribute(string $value)
    {
        $this->attributes['type'] = Str::lower(trim($value));
    }

    protected function setValueAttribute(string $value)
    {
        $this->attributes['value'] = Str::lower(trim($value));
    }

    protected function setTtlAttribute(int $value)
    {
        $this->attributes['ttl'] = $value;

        $this->attributes['expired_at'] = Carbon::now()->addDays($value);
    }
}
