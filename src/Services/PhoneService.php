<?php

namespace Helldar\SpammersServer\Services;

use Helldar\SpammersServer\Models\Phone;
use function compact;

class PhoneService extends BaseService
{
    public function store(string $source)
    {
        if (!$this->exists($source, true)) {
            return Phone::create(compact('source'));
        }

        $item = Phone::query()
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
        return Phone::query()
            ->where('source', $source)
            ->delete();
    }

    public function exists(string $source, bool $with_trashed = false): bool
    {
        $query = Phone::where('source', $source);

        if ($with_trashed) {
            $query->withTrashed();
        }

        return $query->exists();
    }
}
