<?php
declare(strict_types=1);

namespace UserSettings\Handler;

use UserSettings\Repository\KeyValueRepositoryInterface;
use UserSettings\Repository\SettingsRepository;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use UserSettings\Response\AppResponse;
use UserSettings\Entity\User;
use UserSettings\Exception\AccessDeniedException;
use function json_decode;
use function sprintf;

class UpdateSettingsConfirmHandler
{
    public function __construct(
        private KeyValueRepositoryInterface $keyValueRepository,
        private SettingsRepository $settingsRepository
    ) {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        /**
         * Считаем, что где то выше прошла авторизация
         *
         * @var User $user
         */
        $user = $request->getAttribute(User::class);

        //POST запрос с json данными. Тут лучше реализовать JsonDecoderInterface
        $payload = json_decode((string) $request->getBody(), true);

        //Данные можно валидировать и писать в объект реквеста, где то выше в мидлваре
        $oneTimeCode = $payload['code'];
        //лучше вынести в KeyGenerator, потому что мы переиспользуем логику создания ключа
        $key = sprintf('%s:%s', $user->getUuid(), $oneTimeCode);

        $data = $this->keyValueRepository->get($key);
        if (!$data) {
            //время истекло, либо такого ключа и не было. Отдаем какой то 422 BadRequest или 404 NotFound
            throw new AccessDeniedException();
        }

        $settingUuid = $data['uuid'];
        $settingValue = $data['value'];

        //проверяем есть ли такие настройки, и принадлежат ли они текущему пользователю
        //можно добавить мидлварь с acl или rbac для этого
        $settings = $this->settingsRepository->get($settingUuid);
        if ($settings->getUserUuid() !== $user->getUuid()) {
            throw new AccessDeniedException();
        }

        $settings->setValue($settingValue);
        $this->settingsRepository->save($settings);

        //отдаем какой то 200 OK ответ
        return new AppResponse();
    }
}