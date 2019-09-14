<?php

namespace Helldar\BlacklistServer\Services;

use Carbon\Carbon;
use Helldar\BlacklistCore\Exceptions\BlacklistDetectedException;
use Helldar\BlacklistCore\Facades\Validator;
use Helldar\BlacklistServer\Contracts\Service;
use Helldar\BlacklistServer\Models\Blacklist;
use Illuminate\Database\Eloquent\Builder;

use function compact;
use function config;

class BlacklistService implements Service
{
    protected $ttl;
    protected $ttl_multiplier;

    public function __construct()
    {
        $this->ttl = (int) config('blacklist_server.ttl', 7);

        $this->ttl_multiplier = (int) config('blacklist_server.ttl_multiplier', 3);
    }

    public function store(string $type, string $value)
    : Blacklist {
        $this->validate($type, $value);

        if (! $this->exists($value, false)) {
            $ttl = $this->ttl;

            return Blacklist::create(compact('type', 'value', 'ttl'));
        }

        $item = Blacklist::findOrFail($value);

        $item->update([
            'ttl' => $item->ttl * $this->ttl_multiplier,
        ]);

        return $item;
    }

    /**
     * @param string $value
     *
     * @throws BlacklistDetectedException
     * @return bool
     */
    public function check(string $value)
    : bool {
        $this->validate($type, $value, false);

        if ($this->exists($value)) {
            throw new BlacklistDetectedException($type, $value);
        }

        return true;
    }

    public function exists(string $value, bool $only_actually = true)
    : bool {
        return Blacklist::query()
            ->where('value', $value)
            ->where(function (Builder $builder) use ($only_actually) {
                if ($only_actually) {
                    $builder->where('expired_at', '>', Carbon::now());
                }
            })
            ->exists();
    }

    private function validate(string $type = null, string $value = null, bool $is_require_type = true)
    {
        Validator::validate($type, $value, $is_require_type);
    }
}