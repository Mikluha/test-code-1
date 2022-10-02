<?php
declare(strict_types=1);

namespace UserSettings\ValueObject;

interface MessageInterface
{
    public function getType(): string;

    public function getRecipient(): string;

    public function getMessage(): string;
}