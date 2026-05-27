<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidSupport\Heartbeat\Component\DiagnosticsProvider\Service;

use OxidEsales\EshopCommunity\Internal\Framework\Module\Configuration\Bridge\ModuleSettingBridgeInterface;
use OxidSupport\Heartbeat\Module\Module;

/**
 * Service for checking the Diagnostics Provider component status.
 */
final class DiagnosticsProviderStatusService implements DiagnosticsProviderStatusServiceInterface
{
    private ModuleSettingBridgeInterface $moduleSettingService;

    public function __construct(ModuleSettingBridgeInterface $moduleSettingService)
    {
        $this->moduleSettingService = $moduleSettingService;
    }

    /**
     * @inheritDoc
     */
    public function isActive(): bool
    {
        try {
            return (bool) $this->moduleSettingService->get(Module::SETTING_DIAGNOSTICSPROVIDER_ACTIVE, Module::ID);
        } catch (\Throwable $e) {
            return false;
        }
    }
}
