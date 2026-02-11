<?php

declare(strict_types=1);

namespace OxidSupport\Heartbeat\Shop\Facade;

use OxidEsales\EshopCommunity\Internal\Framework\Module\Facade\ModuleSettingServiceInterface;
use OxidSupport\Heartbeat\Module\Module;

class ModuleSettingFacade implements ModuleSettingFacadeInterface
{
    private ModuleSettingServiceInterface $moduleSettingService;

    public function __construct(ModuleSettingServiceInterface $moduleSettingService)
    {
        $this->moduleSettingService = $moduleSettingService;
    }

    public function getLogLevel(): string
    {
        return (string) $this->moduleSettingService->getString(
            Module::SETTING_REQUESTLOGGER_LOG_LEVEL,
            Module::ID
        );
    }

    /** @return array<int|string, string> */
    public function getRedactItems(): array
    {
        /** @var array<int|string, string> */
        return $this->moduleSettingService->getCollection(
            Module::SETTING_REQUESTLOGGER_REDACT_FIELDS,
            Module::ID
        );
    }

    public function isRedactAllValuesEnabled(): bool
    {
        return $this->moduleSettingService->getBoolean(
            Module::SETTING_REQUESTLOGGER_REDACT_ALL_VALUES,
            Module::ID
        );
    }

    public function isLogFrontendEnabled(): bool
    {
        return $this->moduleSettingService->getBoolean(
            Module::SETTING_REQUESTLOGGER_LOG_FRONTEND,
            Module::ID
        );
    }

    public function isLogAdminEnabled(): bool
    {
        return $this->moduleSettingService->getBoolean(
            Module::SETTING_REQUESTLOGGER_LOG_ADMIN,
            Module::ID
        );
    }

    public function isRequestLoggerComponentActive(): bool
    {
        return $this->moduleSettingService->getBoolean(
            Module::SETTING_REQUESTLOGGER_ACTIVE,
            Module::ID
        );
    }
}
