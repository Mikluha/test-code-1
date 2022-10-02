<?php
declare(strict_types=1);

namespace UserSettings\Sender;

use UserSettings\Enum\SenderTypeEnum;
use UserSettings\Exception\UnsupportedSenderException;

class SenderFactory
{
    public function create(string $type): SenderInterface
    {
        /**
         * матчинг типов с классами нужно унести в конфиг и принимать в конструкторе,
         * чтобы не модифицировать фабрику, при добавлении новой страгегии.
         */
        return match ($type) {
            SenderTypeEnum::SMS => new SmsSender(),
            SenderTypeEnum::TELEGRAM => new TelegramSender(),
            SenderTypeEnum::EMAIl => new EmailSender(),
            'default' => throw new UnsupportedSenderException($type)
        };
    }
}