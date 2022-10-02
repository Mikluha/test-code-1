<?php
declare(strict_types=1);

namespace UserSettings\Entity;

class Settings
{
    /**
     * Настройки храним в БД.
     * uuid - идентификатор записи
     * userUuid - связь с пользователем
     * name - имя настройки
     * value - значение настройки
     *
     * CREATE TABLE `user_settings` (
     * `uuid` binary(16) NOT NULL',
     * `user_uuid` binary(16) NOT NULL,
     * `name` varchar(255) NOT NULL,
     * `value` longtext NOT NULL,
     * PRIMARY KEY (`uuid`),
     * UNIQUE KEY `unq_user_settings_user_uuid_name` (`user_uuid`,`name`),
     * KEY `fk_user_settings_user` (`user_uuid`),
     * CONSTRAINT `fk_user_settings_user` FOREIGN KEY (`user_uuid`) REFERENCES `user` (`uuid`) ON DELETE CASCADE
     */
    public function __construct(
        private string $uuid,
        private string $userUuid,
        private string $name,
        private string $value
    ) {
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function getUserUuid(): string
    {
        return $this->userUuid;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function setValue(string $value): void
    {
        $this->value = $value;
    }
}