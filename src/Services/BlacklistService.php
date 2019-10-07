<?php

namespace Helldar\BlacklistServer\Services;

use Helldar\BlacklistCore\Contracts\ServiceContract;
use Helldar\BlacklistCore\Exceptions\BlacklistDetectedException;
use Helldar\BlacklistServer\Facades\Validator;
use Helldar\BlacklistServer\Models\Blacklist;

use function compact;
use function config;

class BlacklistService implements ServiceContract
{
    protected $ttl;

    protected $ttl_multiplier;

    public function __construct()
    {
        $this->ttl = (int) config('blacklist_server.ttl', 3);

        $this->ttl_multiplier = (int) config('blacklist_server.ttl_multiplier', 2);
    }

    public function store(string $value = null, string $type = null): Blacklist
    {
        $this->validate(compact('value', 'type'));

        if (! $this->exists($value, false)) {
            $ttl   = $this->ttl;
            $value = $this->clearPhone($value);

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
     * @param string|null $type
     *
     * @throws \Helldar\BlacklistCore\Exceptions\BlacklistDetectedException
     */
    public function check(string $value = null, string $type = null): void
    {
        $this->validate(compact('value', 'type'), false);

        if ($this->exists($value)) {
            throw new BlacklistDetectedException($value);
        }
    }

    public function exists(string $value = null, string $type = null): bool
    {
        $this->validate(compact('value', 'type'), false);

        $value = $this->clearPhone($value, $type);

        return Blacklist::query()
            ->where('value', $value)
            ->exists();
    }

    private function validate(array $data, bool $is_require_type = true)
    {
        Validator::validate($data, $is_require_type);
    }

    private function clearPhone(string $value, string $type = null): string
    {
        if ($type !== 'phone') {
            return \trim($value);
        }

        return (string) \preg_replace("/\D/", '', $value);
    }
}
