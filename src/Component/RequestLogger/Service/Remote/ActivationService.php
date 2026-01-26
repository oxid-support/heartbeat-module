<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidSupport\Heartbeat\Component\RequestLogger\Service\Remote;

use OxidEsales\GraphQL\ConfigurationAccess\Module\Service\ModuleSettingServiceInterface as ConfigAccessSettingService;
use OxidSupport\Heartbeat\Module\Module as RequestLoggerModule;

final readonly class ActivationService implements ActivationServiceInterface
{
    private const SETTING_ACTIVE = RequestLoggerModule::SETTING_REQUESTLOGGER_ACTIVE;

    public function __construct(
        private ConfigAccessSettingService $moduleSettingService
    ) {
    }

    public function activate(): bool
    {
        return $this->moduleSettingService
            ->changeBooleanSetting(self::SETTING_ACTIVE, true, RequestLoggerModule::ID)
            ->getValue();
    }

    public function deactivate(): bool
    {
        $this->moduleSettingService
            ->changeBooleanSetting(self::SETTING_ACTIVE, false, RequestLoggerModule::ID);

        return true;
    }

    public function isActive(): bool
    {
        return $this->moduleSettingService
            ->getBooleanSetting(self::SETTING_ACTIVE, RequestLoggerModule::ID)
            ->getValue();
    }
}
