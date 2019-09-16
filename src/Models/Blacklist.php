<?php

namespace Helldar\BlacklistServer\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

use function config;
use function trim;

class Blacklist extends Model
{
    public $incrementing = false;

    protected $primaryKey = 'value';

    protected $keyType = 'string';

    protected $fillable = ['value', 'type', 'ttl', 'expired_at'];

    protected $dates = ['expired_at'];

    protected $casts = [
        'ttl' => 'integer',
    ];

    protected $hidden = ['ttl'];

    public function __construct(array $attributes = [])
    {
        $this->setConnection(config('blacklist_server.connection', 'mysql'));

        parent::__construct($attributes);
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
