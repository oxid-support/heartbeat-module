<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidSupport\Heartbeat\Component\DiagnosticsProvider\Service;

use OxidEsales\EshopCommunity\Internal\Framework\Module\Facade\ModuleSettingServiceInterface;
use OxidSupport\Heartbeat\Module\Module;

/**
 * Service for checking the Diagnostics Provider component status.
 */
final readonly class DiagnosticsProviderStatusService implements DiagnosticsProviderStatusServiceInterface
{
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
            return $this->moduleSettingService->getBoolean(Module::SETTING_DIAGNOSTICSPROVIDER_ACTIVE, Module::ID);
        } catch (\Throwable) {
            return false;
        }
    }
}
