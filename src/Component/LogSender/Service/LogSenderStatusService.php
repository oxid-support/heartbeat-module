<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidSupport\Heartbeat\Component\LogSender\Service;

use OxidEsales\EshopCommunity\Internal\Framework\Module\Configuration\Bridge\ModuleSettingBridgeInterface;
use OxidSupport\Heartbeat\Module\Module;

/**
 * Service for checking the Log Sender component status.
 */
final class LogSenderStatusService implements LogSenderStatusServiceInterface
{
    private const DEFAULT_MAX_BYTES = 1048576; // 1 MB

    public function __construct(
        private ModuleSettingBridgeInterface $moduleSettingService
    ) {
    }

    /**
     * @inheritDoc
     */
    public function isActive(): bool
    {
        try {
            return (bool) $this->moduleSettingService->get(Module::SETTING_LOGSENDER_ACTIVE, Module::ID);
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
            $maxBytes = (int) $this->moduleSettingService->get(Module::SETTING_LOGSENDER_MAX_BYTES, Module::ID);
            return $maxBytes > 0 ? $maxBytes : self::DEFAULT_MAX_BYTES;
        } catch (\Throwable) {
            return self::DEFAULT_MAX_BYTES;
        }
    }
}
