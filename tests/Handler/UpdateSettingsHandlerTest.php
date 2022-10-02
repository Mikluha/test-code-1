<?php
declare(strict_types=1);

namespace Handler;

use PHPUnit\Framework\TestCase;
use UserSettings\Entity\User;
use Psr\Http\Message\ServerRequestInterface;
use UserSettings\Repository\SettingsRepository;
use UserSettings\Entity\Settings;
use UserSettings\Service\CodeGeneratorInterface;
use UserSettings\Repository\KeyValueRepositoryInterface;
use UserSettings\Handler\UpdateSettingsHandler;
use UserSettings\Sender\SenderInterface;
use UserSettings\Exception\AccessDeniedException;
use UserSettings\ValueObject\MessageInterface;

/**
 * @package UserSettingsTest\Handler
 * @covers  \UserSettings\Handler\UpdateSettingsHandler
 */
class UpdateSettingsHandlerTest extends TestCase
{
    public function testHandle_UserMismatch_ThrowsException(): void
    {
        $user = $this->createMock(User::class);
        $user->method('getUuid')->willReturn('uUuid1');

        $request = $this->createMock(ServerRequestInterface::class);
        $request->method('getAttribute')->with(User::class)->willReturn($user);
        $request->method('getBody')->willReturn('{"uuid": "sUuid", "value": "value", "type": "type"}');

        $settings = $this->createMock(Settings::class);
        $settings->method('getUserUuid')->willReturn('uUuid2');

        $settingsRepository = $this->createMock(SettingsRepository::class);
        $settingsRepository->method('get')->with('sUuid')->willReturn($settings);

        $codeGenerator = $this->createMock(CodeGeneratorInterface::class);
        $keyValueRepository = $this->createMock(KeyValueRepositoryInterface::class);
        $sender = $this->createMock(SenderInterface::class);

        $handler = new UpdateSettingsHandler($keyValueRepository, $settingsRepository, $codeGenerator, $sender);

        $this->expectException(AccessDeniedException::class);

        $handler->handle($request);
    }

    public function testHandle_Request_CodeSends(): void
    {
        $user = $this->createMock(User::class);
        $user->method('getUuid')->willReturn('uUuid');
        $user->method('getPhone')->willReturn('phone');

        $request = $this->createMock(ServerRequestInterface::class);
        $request->method('getAttribute')->with(User::class)->willReturn($user);
        $request->method('getBody')->willReturn('{"uuid": "sUuid", "value": "value", "type": "type"}');

        $settings = $this->createMock(Settings::class);
        $settings->method('getUserUuid')->willReturn('uUuid');

        $settingsRepository = $this->createMock(SettingsRepository::class);
        $settingsRepository->method('get')->with('sUuid')->willReturn($settings);

        $codeGenerator = $this->createMock(CodeGeneratorInterface::class);
        $codeGenerator->method('generate')->with(5)->willReturn(12345);

        $keyValueRepository = $this->createMock(KeyValueRepositoryInterface::class);
        $keyValueRepository->expects($this->once())->method('set')->with('uUuid:12345', [
            'uuid' => 'sUuid',
            'value' => 'value',
        ], 60.0);

        $sender = $this->createMock(SenderInterface::class);
        $sender->expects($this->once())->method('send')->with(
            $this->callback(function (MessageInterface $message): bool {
                return $message->getType() === 'type' &&
                    $message->getMessage() === '12345' &&
                    $message->getRecipient() === 'phone';
            })
        );

        $handler = new UpdateSettingsHandler($keyValueRepository, $settingsRepository, $codeGenerator, $sender);
        $handler->handle($request);
    }
}