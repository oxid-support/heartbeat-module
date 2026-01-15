<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidSupport\LoggingFramework\Component\LogSender\Service;

use OxidEsales\EshopCommunity\Internal\Framework\Module\Facade\ModuleSettingServiceInterface;
use OxidSupport\LoggingFramework\Component\LogSender\Exception\LogSenderDisabledException;
use OxidSupport\LoggingFramework\Module\Module;

/**
 * Service for checking the Log Sender component status.
 */
final readonly class LogSenderStatusService implements LogSenderStatusServiceInterface
{
    private const DEFAULT_MAX_BYTES = 1048576; // 1 MB

    public function __construct(
        private ModuleSettingServiceInterface $moduleSettingService
    ) {
    }

    /**
     * @inheritDoc
     */
    public function isActive(): bool
    {
        try {
            return $this->moduleSettingService->getBoolean(Module::SETTING_LOGSENDER_ACTIVE, Module::ID);
        } catch (\Throwable) {
            return false;
        }
    }

    /**
     * @inheritDoc
     */
    public function getMaxBytes(): int
    {
        try {
            $maxBytes = $this->moduleSettingService->getInteger(Module::SETTING_LOGSENDER_MAX_BYTES, Module::ID);
            return $maxBytes > 0 ? $maxBytes : self::DEFAULT_MAX_BYTES;
        } catch (\Throwable) {
            return self::DEFAULT_MAX_BYTES;
        }
    }

    /**
     * @inheritDoc
     */
    public function assertComponentActive(): void
    {
        if (!$this->isActive()) {
            throw new LogSenderDisabledException();
        }
    }
}
