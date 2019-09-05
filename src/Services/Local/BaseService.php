<?php

namespace Helldar\SpammersServer\Services\Local;

use function config;

abstract class BaseService
{
    protected $ttl_multiplier;

    public function __construct()
    {
        $this->ttl_multiplier = (int) config('spammers_server.ttl_multiplier');
    }

    abstract public function store(string $source);

    abstract public function delete(string $source): int;

    abstract public function exists(string $source, bool $with_trashed = false): bool;
}
