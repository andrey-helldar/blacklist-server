<?php

namespace Helldar\SpammersServer\Services;

use Helldar\SpammersServer\Models\Ip;
use function compact;

class IpService extends BaseService
{
    public function store(string $source)
    {
        if (!$this->exists($source, true)) {
            return Ip::create(compact('source'));
        }

        $item = Ip::query()
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
        return Ip::query()
            ->where('source', $source)
            ->delete();
    }

    public function exists(string $source, bool $with_trashed = false): bool
    {
        $query = Ip::where('source', $source);

        if ($with_trashed) {
            $query->withTrashed();
        }

        return $query->exists();
    }
}
