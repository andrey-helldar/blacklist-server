<?php

namespace Helldar\SpammersServer\Services;

use Helldar\SpammersServer\Contracts\Service;
use Helldar\SpammersServer\Exceptions\SpammerDetectedException;
use Helldar\SpammersServer\Facades\Helpers\Validator;
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
        $this->ttl = (int) config('spammers_server.ttl', 7);

        $this->ttl_multiplier = (int) config('spammers_server.ttl_multiplier', 3);
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
     * @throws \Helldar\SpammersServer\Exceptions\SpammerDetectedException
     * @return bool
     */
    public function check(string $source = null): bool
    {
        $this->validate($source);

        if ($this->exists($source)) {
            $type = class_basename($this->model);

            throw new SpammerDetectedException($type, $source);
        }

        return true;
    }

    protected function validate(string $source = null)
    {
        Validator::validate($this->model, $source);
    }
}
