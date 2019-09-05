<?php

namespace Helldar\SpammersServer\Contracts;

interface Service
{
    public function store(string $source);

    public function delete(string $source): int;

    public function exists(string $source, bool $with_trashed): bool;
}
