<?php
declare(strict_types=1);

namespace UserSettings\Sender;

use UserSettings\ValueObject\MessageInterface;

/**
 * Стратегию мы будем отдавать тем, кто ждет SenderInterface
 */
class SenderStrategy implements SenderInterface
{
    public function __construct(private SenderFactory $senderFactory)
    {
    }

    public function send(MessageInterface $message): void
    {
        $sender = $this->senderFactory->create($message->getType());
        $sender->send($message);
    }
}