<?php
declare(strict_types=1);

namespace UserSettings\Sender;

use UserSettings\ValueObject\MessageInterface;

class TelegramSender implements SenderInterface
{
    public function send(MessageInterface $message): void
    {
        // TODO: Implement send() method.
    }
}