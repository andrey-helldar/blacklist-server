<?php

namespace Helldar\BlacklistServer\Services;

use Helldar\BlacklistCore\Exceptions\BlacklistDetectedException;
use Helldar\BlacklistServer\Contracts\Service;
use Helldar\BlacklistServer\Facades\Helpers\Validator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use function class_basename;
use function config;

abstract class BaseService implements Service
{
    /** @var Model */
    protected $model;

    protected $ttl;

    protected $ttl_multiplier;

    public function __construct()
    {
        $this->ttl = (int)config('blacklist_server.ttl', 7);

        $this->ttl_multiplier = (int)config('blacklist_server.ttl_multiplier', 3);
    }

    public function store(string $value = null)
    {
        $this->validate($value);

        if (!$this->exists($value, true)) {
            $ttl = $this->ttl;

            return $this->model::create(compact('value', 'ttl'));
        }

        $item = $this->model::query()
            ->withTrashed()
            ->findOrFail($value);

        $item->update([
            'ttl' => $item->ttl * $this->ttl_multiplier,
            'deleted_at' => null,
        ]);

        return $item;
    }

    public function exists(string $value = null, bool $with_trashed = false): bool
    {
        $this->validate($value);

        $query = $this->model::where('value', $value);

        if ($with_trashed) {
            $query->withTrashed();
        }

        return $query->exists();
    }

    /**
     * @param string|null $value
     *
     * @return int
     * @throws \Exception
     */
    public function delete(string $value = null): int
    {
        $this->validate($value);

        return $this->model::query()
            ->findOrFail($value)
            ->delete();
    }

    /**
     * @param string|null $value
     *
     * @return bool
     *
     * @throws BlacklistDetectedException
     */
    public function check(string $value = null): bool
    {
        if ($this->exists($value)) {
            $type = class_basename($this->model);

            throw new BlacklistDetectedException($type, $value);
        }

        return true;
    }

    protected function validate(string $value = null)
    {
        $type = Str::lower(\class_basename($this->model));

        Validator::validate($type, $value);
    }
}
