<?php
declare(strict_types=1);

namespace UserSettings\Repository;

interface KeyValueRepositoryInterface
{
    public function get(string $key): ?array;

    public function set(string $key, array $value, float $ttl): void;
}