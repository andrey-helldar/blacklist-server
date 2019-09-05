<?php

namespace Helldar\SpammersServer\Services\Local;

use Helldar\SpammersServer\Models\Email;
use Helldar\SpammersServer\Services\BaseService;

class EmailService extends BaseService
{
    public function store(string $source)
    {
        if (!$this->exists($source, true)) {
            return Email::create(\compact('source'));
        }
        $item = Email::query()
            ->withTrashed()
            ->findOrFail($source);

        $item->update([
            'ttl'        => $item->ttl * $this->ttl_multiplier,
            'deleted_at' => null,
        ]);

        return $item;
    }

    public function delete(string $source): int
    {
        return Email::query()
            ->where('source', $source)
            ->delete();
    }

    public function exists(string $source, bool $with_trashed = false): bool
    {
        $query = Email::where('source', $source);

        if ($with_trashed) {
            $query->withTrashed();
        }

        return $query->exists();
    }
}
