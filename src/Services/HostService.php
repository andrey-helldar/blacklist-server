<?php

namespace Helldar\SpammersServer\Services;

use Helldar\SpammersServer\Models\Host;
use function compact;

class HostService extends BaseService
{
    public function store(string $source)
    {
        if (!$this->exists($source, true)) {
            return Host::create(compact('source'));
        }

        $item = Host::query()
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
        return Host::query()
            ->where('source', $source)
            ->delete();
    }

    public function exists(string $source, bool $with_trashed = false): bool
    {
        $query = Host::where('source', $source);

        if ($with_trashed) {
            $query->withTrashed();
        }

        return $query->exists();
    }
}
