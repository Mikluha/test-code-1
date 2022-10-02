<?php
declare(strict_types=1);

namespace Handler;

use PHPUnit\Framework\TestCase;
use UserSettings\Entity\User;
use Psr\Http\Message\ServerRequestInterface;
use UserSettings\Repository\SettingsRepository;
use UserSettings\Entity\Settings;
use UserSettings\Repository\KeyValueRepositoryInterface;
use UserSettings\Handler\UpdateSettingsConfirmHandler;
use UserSettings\Exception\AccessDeniedException;

/**
 * @package UserSettingsTest\Handler
 * @covers  \UserSettings\Handler\UpdateSettingsConfirmHandler
 */
class UpdateSettingsConfirmHandlerTest extends TestCase
{
    public function testHandle_MissingValue_ThrowsException(): void
    {
        $user = $this->createMock(User::class);
        $user->method('getUuid')->willReturn('uUuid');

        $request = $this->createMock(ServerRequestInterface::class);
        $request->method('getAttribute')->with(User::class)->willReturn($user);
        $request->method('getBody')->willReturn('{"code": "12345"}');

        $settingsRepository = $this->createMock(SettingsRepository::class);

        $keyValueRepository = $this->createMock(KeyValueRepositoryInterface::class);
        $keyValueRepository->method('get')->with('uUuid:12345')->willReturn(null);

        $handler = new UpdateSettingsConfirmHandler($keyValueRepository, $settingsRepository);

        $this->expectException(AccessDeniedException::class);

        $handler->handle($request);
    }

    public function testHandle_UserMismatch_ThrowsException(): void
    {
        $user = $this->createMock(User::class);
        $user->method('getUuid')->willReturn('uUuid1');

        $request = $this->createMock(ServerRequestInterface::class);
        $request->method('getAttribute')->with(User::class)->willReturn($user);
        $request->method('getBody')->willReturn('{"code": "12345"}');

        $keyValueRepository = $this->createMock(KeyValueRepositoryInterface::class);
        $keyValueRepository->method('get')->with('uUuid1:12345')->willReturn(['uuid' => 'sUuid', 'value' => 'value']);

        $settings = $this->createMock(Settings::class);
        $settings->method('getUserUuid')->willReturn('uUuid2');

        $settingsRepository = $this->createMock(SettingsRepository::class);
        $settingsRepository->method('get')->with('sUuid')->willReturn($settings);

        $handler = new UpdateSettingsConfirmHandler($keyValueRepository, $settingsRepository);

        $this->expectException(AccessDeniedException::class);

        $handler->handle($request);
    }

    public function testHandle_Request_SettingsSaves(): void
    {
        $user = $this->createMock(User::class);
        $user->method('getUuid')->willReturn('uUuid');

        $request = $this->createMock(ServerRequestInterface::class);
        $request->method('getAttribute')->with(User::class)->willReturn($user);
        $request->method('getBody')->willReturn('{"code": "12345"}');

        $keyValueRepository = $this->createMock(KeyValueRepositoryInterface::class);
        $keyValueRepository->method('get')->with('uUuid:12345')->willReturn(['uuid' => 'sUuid', 'value' => 'value']);

        $settings = $this->createMock(Settings::class);
        $settings->method('getUserUuid')->willReturn('uUuid');
        $settings->expects($this->once())->method('setValue')->with('value');

        $settingsRepository = $this->createMock(SettingsRepository::class);
        $settingsRepository->method('get')->with('sUuid')->willReturn($settings);
        $settingsRepository->expects($this->once())->method('save')->with($settings);

        $handler = new UpdateSettingsConfirmHandler($keyValueRepository, $settingsRepository);
        $handler->handle($request);
    }
}