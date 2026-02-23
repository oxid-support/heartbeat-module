<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidSupport\Heartbeat\Shared\Controller\Admin;

use OxidEsales\EshopCommunity\Internal\Container\ContainerFactory;
use OxidEsales\EshopCommunity\Internal\Framework\Module\Setting\Bridge\ModuleSettingBridgeInterface;
use OxidSupport\Heartbeat\Component\ApiUser\Service\ApiUserStatusServiceInterface;
use OxidSupport\Heartbeat\Module\Module;

/**
 * Extended NavigationController to add Heartbeat component status indicators.
 *
 * @eshopExtension
 * @mixin \OxidEsales\Eshop\Application\Controller\Admin\NavigationController
 */
class NavigationController extends NavigationController_parent
{
    private const SETTING_REQUESTLOGGER_ACTIVE = Module::SETTING_REQUESTLOGGER_ACTIVE;
    private const SETTING_LOGSENDER_ACTIVE = Module::SETTING_LOGSENDER_ACTIVE;
    private const SETTING_DIAGNOSTICSPROVIDER_ACTIVE = Module::SETTING_DIAGNOSTICSPROVIDER_ACTIVE;

    /**
     * @inheritDoc
     */
    public function render()
    {
        $template = parent::render();

        // Add component status information for the template
        $this->_aViewData['lfComponentStatus'] = $this->getHeartbeatComponentStatus();

        return $template;
    }

    /**
     * Get the activation status of all Heartbeat components.
     *
     * @return array<string, bool>
     */
    public function getHeartbeatComponentStatus(): array
    {
        $moduleSettingService = $this->getModuleSettingService();

        return [
            'heartbeat_apiuser_setup' => $this->isApiUserSetupComplete(),
            'heartbeat_requestlogger_settings' => (bool) $moduleSettingService->get(
                self::SETTING_REQUESTLOGGER_ACTIVE,
                Module::ID
            ),
            'heartbeat_requestlogger_setup' => $this->isApiUserSetupComplete() && (bool) $moduleSettingService->get(
                self::SETTING_REQUESTLOGGER_ACTIVE,
                Module::ID
            ),
            'heartbeat_logsender_manage' => $this->isApiUserSetupComplete()
                && $this->getLogSenderStatus($moduleSettingService),
            'heartbeat_diagnosticsprovider_manage' => $this->isApiUserSetupComplete()
                && $this->getDiagnosticsProviderStatus($moduleSettingService),
        ];
    }

    /**
     * Check if the API User setup is complete.
     */
    private function isApiUserSetupComplete(): bool
    {
        try {
            return $this->getApiUserStatusService()->isSetupComplete();
        } catch (\Exception) {
            return false;
        }
    }

    /**
     * Get Log Sender component status.
     */
    private function getLogSenderStatus(ModuleSettingBridgeInterface $moduleSettingService): bool
    {
        try {
            return (bool) $moduleSettingService->get(
                self::SETTING_LOGSENDER_ACTIVE,
                Module::ID
            );
        } catch (\Throwable) {
            return false;
        }
    }

    /**
     * Get Diagnostics Provider component status.
     */
    private function getDiagnosticsProviderStatus(ModuleSettingBridgeInterface $moduleSettingService): bool
    {
        try {
            return (bool) $moduleSettingService->get(
                self::SETTING_DIAGNOSTICSPROVIDER_ACTIVE,
                Module::ID
            );
        } catch (\Throwable) {
            return false;
        }
    }

    protected function getModuleSettingService(): ModuleSettingBridgeInterface
    {
        return ContainerFactory::getInstance()
            ->getContainer()
            ->get(ModuleSettingBridgeInterface::class);
    }

    protected function getApiUserStatusService(): ApiUserStatusServiceInterface
    {
        return ContainerFactory::getInstance()
            ->getContainer()
            ->get(ApiUserStatusServiceInterface::class);
    }
}
