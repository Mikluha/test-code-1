<?php
declare(strict_types=1);

namespace UserSettings\Repository;

/**
 * KeyValue хранилище, например redis
 */
class KeyValueRepository implements KeyValueRepositoryInterface
{
    public function get(string $key): ?array
    {
        return [];
    }

    public function set(string $key, array $value, float $ttl): void
    {

    }
}