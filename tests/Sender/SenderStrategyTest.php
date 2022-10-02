<?php
declare(strict_types=1);

namespace UserSettingsTest\Sender;

use PHPUnit\Framework\TestCase;
use UserSettings\Sender\SenderFactory;
use UserSettings\ValueObject\MessageInterface;
use UserSettings\Sender\SenderInterface;
use UserSettings\Sender\SenderStrategy;

/**
 * @package Sender
 * @covers  \UserSettings\Sender\SenderStrategy
 */
class SenderStrategyTest extends TestCase
{
    public function testSend_Message_SendCalls(): void
    {
        $message = $this->createMock(MessageInterface::class);
        $message->method('getType')->willReturn('type');

        $sender = $this->createMock(SenderInterface::class);
        $sender->expects($this->once())->method('send')->with($message);

        $senderFactory = $this->createMock(SenderFactory::class);
        $senderFactory->method('create')->with('type')->willReturn($sender);

        $senderStrategy = new SenderStrategy($senderFactory);
        $senderStrategy->send($message);
    }
}