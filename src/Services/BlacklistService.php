<?php

namespace Helldar\BlacklistServer\Services;

use Carbon\Carbon;
use Helldar\BlacklistCore\Constants\Server;
use Helldar\BlacklistCore\Contracts\ServiceContract;
use Helldar\BlacklistCore\Exceptions\BlacklistDetectedException;
use Helldar\BlacklistCore\Exceptions\ExceptBlockingDetected;
use Helldar\BlacklistCore\Exceptions\SelfBlockingDetected;
use Helldar\BlacklistCore\Facades\Validator;
use Helldar\BlacklistServer\Models\Blacklist;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;

use function compact;
use function config;
use function in_array;

class BlacklistService implements ServiceContract
{
    protected $ttl;

    protected $ttl_multiplier;

    public function __construct()
    {
        $this->ttl = (int) config('blacklist_server.ttl', 7);

        $this->ttl_multiplier = (int) config('blacklist_server.ttl_multiplier', 3);
    }

    /**
     * @param array $data
     *
     * @throws ExceptBlockingDetected
     * @throws SelfBlockingDetected
     * @return Blacklist
     */
    public function store(array $data): Blacklist
    {
        $this->validate($data);

        $value = Arr::get($data, 'value');

        $this->checkSelfBlocking($value);
        $this->checkExceptBlocking($value);

        if (! $this->exists($value, false)) {
            $type = Arr::get($data, 'type');
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
     * @param string|null $value
     *
     * @throws \Helldar\BlacklistCore\Exceptions\BlacklistDetectedException
     */
    public function check(string $value = null): void
    {
        $this->validate(compact('value'), false);

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

    private function validate(array $data, bool $is_require_type = true)
    {
        Validator::validate($data, $is_require_type);
    }

    /**
     * @param string $value
     *
     * @throws SelfBlockingDetected
     */
    private function checkSelfBlocking(string $value)
    {
        if (in_array($value, Server::selfValues())) {
            throw new SelfBlockingDetected($value);
        }
    }

    /**
     * @param string $value
     *
     * @throws ExceptBlockingDetected
     */
    private function checkExceptBlocking(string $value)
    {
        $except = config('blacklist_server.except', []);

        if (in_array($value, $except)) {
            throw new ExceptBlockingDetected($value);
        }
    }
}
