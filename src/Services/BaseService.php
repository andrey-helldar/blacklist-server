<?php

namespace Helldar\BlacklistServer\Services;

use Helldar\BlacklistCore\Exceptions\BlacklistDetectedException;
use Helldar\BlacklistServer\Contracts\Service;
use Helldar\BlacklistServer\Facades\Helpers\Validator;
use Illuminate\Support\Str;
use function class_basename;
use function config;

abstract class BaseService implements Service
{
    /** @var \Illuminate\Database\Eloquent\Model */
    protected $model;

    protected $ttl;

    protected $ttl_multiplier;

    public function __construct()
    {
        $this->ttl = (int) config('blacklist_server.ttl', 7);

        $this->ttl_multiplier = (int) config('blacklist_server.ttl_multiplier', 3);
    }

    public function store(string $source = null)
    {
        $this->validate($source);

        if (!$this->exists($source, true)) {
            $ttl = $this->ttl;

            return $this->model::create(compact('source', 'ttl'));
        }

        $item = $this->model::query()
            ->withTrashed()
            ->findOrFail($source);

        $item->update([
            'ttl'        => $item->ttl * $this->ttl_multiplier,
            'deleted_at' => null,
        ]);

        return $item;
    }

    /**
     * @param string|null $source
     *
     * @throws \Exception
     * @return int
     */
    public function delete(string $source = null): int
    {
        $this->validate($source);

        return $this->model::query()
            ->findOrFail($source)
            ->delete();
    }

    public function exists(string $source = null, bool $with_trashed = false): bool
    {
        $this->validate($source);

        $query = $this->model::where('source', $source);

        if ($with_trashed) {
            $query->withTrashed();
        }

        return $query->exists();
    }

    /**
     * @param string|null $source
     *
     * @throws \Helldar\BlacklistCore\Exceptions\BlacklistDetectedException
     * @return bool
     */
    public function check(string $source = null): bool
    {
        $this->validate($source);

        if ($this->exists($source)) {
            $type = class_basename($this->model);

            throw new BlacklistDetectedException($type, $source);
        }

        return true;
    }

    protected function validate(string $source = null)
    {
        $type = Str::lower(\class_basename($this->model));

        Validator::validate($type, $source);
    }
}
