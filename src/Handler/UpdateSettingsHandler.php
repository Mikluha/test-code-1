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
use UserSettings\Service\CodeGeneratorInterface;
use UserSettings\Sender\SenderInterface;
use UserSettings\ValueObject\Message;
use UserSettings\Enum\SenderTypeEnum;
use function json_decode;
use function sprintf;

class UpdateSettingsHandler
{
    public function __construct(
        private KeyValueRepositoryInterface $keyValueRepository,
        private SettingsRepository $settingsRepository,
        private CodeGeneratorInterface $codeGenerator,
        private SenderInterface $sender
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

        //Данные можно валидировать и писать в объект респонса, где то выше в мидлваре
        $settingUuid = $payload['uuid'];
        $settingValue = $payload['value'];
        $senderType = $payload['type'];

        //проверяем есть ли такие настройки, и принадлежат ли они текущему пользователю
        //можно добавить мидлварь с acl или rbac для этого
        $settings = $this->settingsRepository->get($settingUuid);
        if ($settings->getUserUuid() !== $user->getUuid()) {
            throw new AccessDeniedException();
        }

        //создаем ключ и кладем в KV storage настройки на минуту
        $oneTimeCode = $this->codeGenerator->generate(5);
        //лучше вынести в KeyGenerator, потому что мы переиспользуем логику создания ключа
        $key = sprintf('%s:%s', $user->getUuid(), $oneTimeCode);
        $this->keyValueRepository->set($key, [
            'uuid' => $settingUuid,
            'value' => $settingValue,
        ], 60.0);

        // тут лучше сделать MessageBuilder и там определять адресата и подготавливать сообщение
        $this->sender->send(
            new Message(
                $senderType,
                $senderType === SenderTypeEnum::EMAIl ? $user->getEmail() : $user->getPhone(),
                (string) $oneTimeCode
            )
        );

        //отдаем какой то 200 OK ответ
        return new AppResponse();
    }
}