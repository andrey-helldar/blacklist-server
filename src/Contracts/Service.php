<?php

namespace Helldar\BlacklistServer\Contracts;

use Helldar\BlacklistServer\Models\Blacklist;

interface Service
{
    public function store(string $type, string $value): Blacklist;

    public function check(string $value = null): string;

    public function exists(string $value): bool;
}
