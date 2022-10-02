<?php
declare(strict_types=1);

namespace UserSettings\ValueObject;

class Message implements MessageInterface
{
    public function __construct(private string $type, private string $recipient, private string $message)
    {
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getRecipient(): string
    {
        return $this->recipient;
    }

    public function getMessage(): string
    {
        return $this->message;
    }
}