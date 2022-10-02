<?php
declare(strict_types=1);

namespace UserSettings\Entity;

class User
{
    /**
     * Пользователей храним в БД.
     * uuid - идентификатор записи
     * name - имя пользователя
     * email - емеил пользователя
     * phone - телефон пользователя
     * password - пароль пользователя
     *
     * CREATE TABLE `user` (
     * `uuid` binary(16) NOT NULL',
     * `name` varchar(255) NOT NULL,
     * `email` varchar(255) NOT NULL,
     * `phone` char(11) NOT NULL DEFAULT '',
     * `password` varchar(255) NOT NULL,
     * PRIMARY KEY (`uuid`),
     * UNIQUE KEY `unq_user_email` (`email`),
     * UNIQUE KEY `unq_user_phone` (`phone`),
     */
    public function __construct(
        private string $uuid,
        private string $name,
        private string $email,
        private string $phone,
        private string $password
    ) {
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }
}