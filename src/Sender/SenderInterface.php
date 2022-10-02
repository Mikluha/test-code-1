<?php
declare(strict_types=1);

namespace UserSettings\Sender;

use UserSettings\ValueObject\MessageInterface;

interface SenderInterface
{
    public function send(MessageInterface $message): void;
}