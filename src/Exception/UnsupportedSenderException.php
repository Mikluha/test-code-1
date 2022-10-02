<?php
declare(strict_types=1);

namespace UserSettings\Exception;

use RuntimeException;

class UnsupportedSenderException extends RuntimeException
{
    public function __construct(string $type)
    {
        parent::__construct("Unsupported sender with type {$type}");
    }
}