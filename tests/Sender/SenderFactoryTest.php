<?php
declare(strict_types=1);

namespace UserSettingsTest\Sender;

use PHPUnit\Framework\TestCase;
use UserSettings\Enum\SenderTypeEnum;
use UserSettings\Sender\EmailSender;
use UserSettings\Sender\TelegramSender;
use UserSettings\Sender\SmsSender;
use UserSettings\Sender\SenderFactory;

/**
 * @package UserSettingsTest\Sender
 * @covers  \UserSettings\Sender\SenderFactory
 */
class SenderFactoryTest extends TestCase
{
    /**
     * @dataProvider providerTypes
     */
    public function testCreate_ProvidedType_ReturnsMatchingInstance(string $type, string $instance): void
    {
        $factory = new SenderFactory();

        $this->assertInstanceOf($instance, $factory->create($type));
    }

    public function providerTypes(): array
    {
        return [
            [
                SenderTypeEnum::EMAIl,
                EmailSender::class,
            ],
            [
                SenderTypeEnum::TELEGRAM,
                TelegramSender::class,
            ],
            [
                SenderTypeEnum::SMS,
                SmsSender::class,
            ],
        ];
    }
}