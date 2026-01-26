<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidSupport\Heartbeat\Component\RequestLogger\Service\Remote;

use OxidEsales\EshopCommunity\Internal\Framework\Module\Facade\ModuleSettingServiceInterface;
use OxidSupport\Heartbeat\Module\Module;
use OxidSupport\Heartbeat\Component\RequestLogger\Exception\RemoteComponentDisabledException;

final readonly class RemoteComponentStatusService implements RemoteComponentStatusServiceInterface
{
    public function __construct(
        private ModuleSettingServiceInterface $moduleSettingService
    ) {
    }

    public function isActive(): bool
    {
        return $this->moduleSettingService->getBoolean(Module::SETTING_REMOTE_ACTIVE, Module::ID);
    }

    public function assertComponentActive(): void
    {
        if (!$this->isActive()) {
            throw new RemoteComponentDisabledException();
        }
    }
}
