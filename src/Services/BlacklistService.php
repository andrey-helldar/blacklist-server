<?php

namespace Helldar\BlacklistServer\Services;

use Carbon\Carbon;
use function compact;
use function config;
use Helldar\BlacklistCore\Contracts\ServiceContract;
use Helldar\BlacklistCore\Exceptions\BlacklistDetectedException;
use Helldar\BlacklistCore\Facades\Validator;

use Helldar\BlacklistServer\Models\Blacklist;
use Illuminate\Database\Eloquent\Builder;

class BlacklistService implements ServiceContract
{
    protected $ttl;

    protected $ttl_multiplier;

    public function __construct()
    {
        $this->ttl = (int) config('blacklist_server.ttl', 7);

        $this->ttl_multiplier = (int) config('blacklist_server.ttl_multiplier', 3);
    }

    public function store(string $type, string $value): Blacklist
    {
        $this->validate($type, $value);

        if (!$this->exists($value, false)) {
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
     *
     * @return bool
     */
    public function check(string $value = null): string
    {
        $this->validate(\compact('value'), false);

        if ($this->exists($value)) {
            throw new BlacklistDetectedException($value);
        }

        return true;
    }

    public function exists(string $value, bool $only_actually = true): bool
    {
        return Blacklist::query()
            ->where('value', $value)
            ->where(function (Builder $builder) use ($only_actually) {
                if ($only_actually) {
                    $builder->where('expired_at', '>', Carbon::now());
                }
            })
            ->exists();
    }

    private function validate(array $data, bool $is_require_type = true)
    {
        Validator::validate($data, $is_require_type);
    }
}
