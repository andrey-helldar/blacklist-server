<?php

namespace Helldar\BlacklistServer\Contracts;

interface Service
{
    public function store(string $value = null);

    public function delete(string $value = null): int;

    public function exists(string $value = null, bool $with_trashed = false): bool;

    public function check(string $value = null): bool;
}
