<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidSupport\LoggingFramework\Shared\Controller\Admin;

use OxidEsales\EshopCommunity\Internal\Container\ContainerFactory;
use OxidEsales\EshopCommunity\Internal\Framework\Module\Facade\ModuleSettingServiceInterface;
use OxidSupport\LoggingFramework\Component\ApiUser\Service\ApiUserStatusServiceInterface;
use OxidSupport\LoggingFramework\Module\Module;

/**
 * Extended NavigationController to add Logging Framework component status indicators.
 *
 * @eshopExtension
 * @mixin \OxidEsales\Eshop\Application\Controller\Admin\NavigationController
 */
class NavigationController extends NavigationController_parent
{
    private const SETTING_REQUESTLOGGER_ACTIVE = Module::SETTING_REQUESTLOGGER_ACTIVE;
    private const SETTING_REMOTE_ACTIVE = Module::SETTING_REMOTE_ACTIVE;
    private const SETTING_LOGSENDER_ACTIVE = Module::SETTING_LOGSENDER_ACTIVE;

    /**
     * @inheritDoc
     */
    public function render()
    {
        $template = parent::render();

        // Add component status information for the template
        $this->_aViewData['lfComponentStatus'] = $this->getLoggingFrameworkComponentStatus();

        return $template;
    }

    /**
     * Get the activation status of all Logging Framework components.
     *
     * @return array<string, bool>
     */
    public function getLoggingFrameworkComponentStatus(): array
    {
        $moduleSettingService = $this->getModuleSettingService();

        return [
            'loggingframework_apiuser_setup' => $this->isApiUserSetupComplete(),
            'loggingframework_requestlogger_settings' => $moduleSettingService->getBoolean(
                self::SETTING_REQUESTLOGGER_ACTIVE,
                Module::ID
            ),
            'loggingframework_remote_setup' => $this->isApiUserSetupComplete() && $moduleSettingService->getBoolean(
                self::SETTING_REMOTE_ACTIVE,
                Module::ID
            ),
            'loggingframework_logsender_setup' => $this->isApiUserSetupComplete() && $this->getLogSenderStatus($moduleSettingService),
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
    private function getLogSenderStatus(ModuleSettingServiceInterface $moduleSettingService): bool
    {
        try {
            return $moduleSettingService->getBoolean(
                self::SETTING_LOGSENDER_ACTIVE,
                Module::ID
            );
        } catch (\Throwable) {
            return false;
        }
    }

    protected function getModuleSettingService(): ModuleSettingServiceInterface
    {
        return ContainerFactory::getInstance()
            ->getContainer()
            ->get(ModuleSettingServiceInterface::class);
    }

    protected function getApiUserStatusService(): ApiUserStatusServiceInterface
    {
        return ContainerFactory::getInstance()
            ->getContainer()
            ->get(ApiUserStatusServiceInterface::class);
    }
}
