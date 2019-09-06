<?php

namespace Helldar\SpammersServer\Contracts;

interface Service
{
    public function store(string $source = null);

    public function delete(string $source = null): int;

    public function exists(string $source = null, bool $with_trashed = false): bool;

    public function check(string $source = null): bool;
}
