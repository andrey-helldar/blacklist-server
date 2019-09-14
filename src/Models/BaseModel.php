<?php

namespace Helldar\BlacklistServer\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use function config;

abstract class BaseModel extends Model
{
    use SoftDeletes;

    public $incrementing = false;

    protected $primaryKey = 'value';

    protected $keyType = 'string';

    protected $fillable = ['value', 'ttl', 'expired_at', 'deleted_at'];

    protected $dates = ['expired_at', 'deleted_at'];

    protected $casts = [
        'ttl' => 'integer',
    ];

    protected $hidden = ['ttl', 'deleted_at'];

    public function __construct(array $attributes = [])
    {
        $this->setConnection(config('blacklist_server.connection', 'mysql'));

        parent::__construct($attributes);
    }

    protected function setTtlAttribute(int $value)
    {
        $this->attributes['ttl'] = $value;

        $this->attributes['expired_at'] = Carbon::now()->addDays($value);
    }
}
