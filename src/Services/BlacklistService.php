<?php

namespace Helldar\BlacklistServer\Services;

use Carbon\Carbon;
use Helldar\BlacklistCore\Contracts\ServiceContract;
use Helldar\BlacklistCore\Exceptions\BlacklistDetectedException;
use Helldar\BlacklistCore\Facades\Validator;
use Helldar\BlacklistServer\Models\Blacklist;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

use function compact;
use function config;

class BlacklistService implements ServiceContract
{
    protected $ttl;

    protected $ttl_multiplier;

    public function __construct()
    {
        $this->ttl = (int) config('blacklist_server.ttl', 7);

        $this->ttl_multiplier = (int) config('blacklist_server.ttl_multiplier', 3);
    }

    public function store(Request $request): Blacklist
    {
        $this->validate($request);

        $value = $request->get('value');

        if (! $this->exists($value, false)) {
            $type = $request->get('type');
            $ttl  = $this->ttl;

            return Blacklist::create(compact('type', 'value', 'ttl'));
        }

        $item = Blacklist::findOrFail($value);

        if (! $item->is_active) {
            $item->update([
                'ttl' => $item->ttl * $this->ttl_multiplier,
            ]);
        }

        return $item;
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @throws \Helldar\BlacklistCore\Exceptions\BlacklistDetectedException
     */
    public function check(Request $request): void
    {
        $this->validate($request, false);

        $value = $request->get('value');

        if ($this->exists($value)) {
            throw new BlacklistDetectedException($value);
        }
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

    private function validate(Request $request, bool $is_require_type = true)
    {
        Validator::validate($request, $is_require_type);
    }
}
