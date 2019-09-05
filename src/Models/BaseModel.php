<?php

namespace Helldar\SpammersServer\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use function config;

abstract class BaseModel extends Model
{
    use SoftDeletes;

    public $incrementing = false;

    protected $primaryKey = 'string';

    protected $fillable = ['source', 'ttl', 'expired_at', 'deleted_at'];

    protected $dates = ['expired_at', 'deleted_at'];

    protected $casts = [
        'ttl' => 'integer',
    ];

    public function __construct(array $attributes = [])
    {
        $this->setConnection(config('spammers_server.connection', 'mysql'));

        parent::__construct($attributes);
    }

    protected function setTtlAttribute(int $value)
    {
        $this->attributes['ttl'] = $value;

        $this->attributes['expired_at'] = Carbon::now()->addDays($value);
    }
}
