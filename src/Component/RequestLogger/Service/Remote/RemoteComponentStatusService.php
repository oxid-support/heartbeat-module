<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidSupport\Heartbeat\Component\RequestLogger\Service\Remote;

use OxidEsales\EshopCommunity\Internal\Framework\Module\Configuration\Bridge\ModuleSettingBridgeInterface;
use OxidSupport\Heartbeat\Module\Module;
use OxidSupport\Heartbeat\Component\RequestLogger\Exception\RemoteComponentDisabledException;

final class RemoteComponentStatusService implements RemoteComponentStatusServiceInterface
{
    public function __construct(
        private ModuleSettingBridgeInterface $moduleSettingService
    ) {
    }

    public function isActive(): bool
    {
        return (bool) $this->moduleSettingService->get(Module::SETTING_REQUESTLOGGER_ACTIVE, Module::ID);
    }

    public function assertComponentActive(): void
    {
        if (!$this->isActive()) {
            throw new RemoteComponentDisabledException();
        }
    }
}
