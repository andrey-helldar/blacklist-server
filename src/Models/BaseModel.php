<?php

namespace Helldar\SpammersServer\Models;

use Illuminate\Database\Eloquent\Model;

abstract class BaseModel extends Model
{
    public $incrementing = false;

    protected $primaryKey = 'string';

    protected $fillable = ['source', 'ttl', 'deleted_at'];

    protected $dates = ['deleted_at'];

    protected $casts = [
        'ttl' => 'integer',
    ];

    public function __construct(array $attributes = [])
    {
        $this->setConnection(\config('spammers_server.connection', 'mysql'));

        parent::__construct($attributes);
    }
}
