<?php
declare(strict_types=1);

namespace UserSettings\Repository;

use UserSettings\Entity\Settings;

/**
 * Репозиторий для работы с БД
 *
 * @package UserSettings\Repository
 */
class SettingsRepository
{
    public function save(Settings $settings): void
    {
        //todo save logic
    }

    public function get($uuid): Settings
    {
        return new Settings($uuid, 'userUuid', 'name', 'value');
    }
}