<?php

namespace Helldar\BlacklistServer\Models;

use Carbon\Carbon;
use Helldar\BlacklistCore\Helpers\Str;
use Illuminate\Database\Eloquent\Model;

use function abs;
use function array_merge;
use function config;
use function request;
use function trim;

class Blacklist extends Model
{
    public $incrementing = false;

    protected $appends = ['is_active'];

    protected $casts = [
        'ttl' => 'integer',
    ];

    protected $dates = ['expired_at'];

    protected $fillable = ['value', 'type', 'ttl', 'source', 'expired_at'];

    protected $hidden = ['ttl', 'source', 'is_active'];

    protected $keyType = 'string';

    protected $primaryKey = 'value';

    public function __construct(array $attributes = [])
    {
        $this->setConnection(config('blacklist_server.connection', 'mysql'));

        parent::__construct(array_merge($attributes, [
            'source' => request()->getClientIp() ?? null,
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
        $this->attributes['ttl'] = abs($value);

        $this->attributes['expired_at'] = Carbon::now()->addDays(abs($value));
    }

    protected function getIsActiveAttribute(): bool
    {
        return $this->expired_at > Carbon::now();
    }
}
