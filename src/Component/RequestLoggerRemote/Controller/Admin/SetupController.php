<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidSupport\LoggingFramework\Component\RequestLoggerRemote\Controller\Admin;

use OxidEsales\EshopCommunity\Internal\Container\ContainerFactory;
use OxidSupport\LoggingFramework\Module\Module;
use OxidSupport\LoggingFramework\Component\ApiUser\Service\ApiUserStatusServiceInterface;
use OxidSupport\LoggingFramework\Shared\Controller\Admin\AbstractComponentController;
use OxidSupport\LoggingFramework\Shared\Controller\Admin\TogglableComponentInterface;

/**
 * Request Logger Remote setup controller for the Logging Framework.
 * Displays the component activation and requires API User to be set up first.
 */
class SetupController extends AbstractComponentController implements TogglableComponentInterface
{
    protected $_sThisTemplate = '@oxsloggingframework/admin/loggingframework_remote_setup';

    private const CONFIG_ACCESS_MODULE_ID = 'oe_graphql_configuration_access';

    private ?ApiUserStatusServiceInterface $apiUserStatusService = null;

    public function isComponentActive(): bool
    {
        return $this->getModuleSettingService()->getBoolean(
            Module::SETTING_REMOTE_ACTIVE,
            Module::ID
        );
    }

    /**
     * Custom status class: warning if API User not set up.
     */
    public function getStatusClass(): string
    {
        if (!$this->isApiUserSetupComplete()) {
            return self::STATUS_CLASS_WARNING;
        }
        return parent::getStatusClass();
    }

    /**
     * Custom status text: warning message if API User not set up.
     */
    public function getStatusTextKey(): string
    {
        if (!$this->isApiUserSetupComplete()) {
            return 'OXSLOGGINGFRAMEWORK_REMOTE_STATUS_WARNING';
        }
        return parent::getStatusTextKey();
    }

    public function toggleComponent(): void
    {
        if (!$this->canToggle()) {
            return;
        }

        $this->getModuleSettingService()->saveBoolean(
            Module::SETTING_REMOTE_ACTIVE,
            !$this->isComponentActive(),
            Module::ID
        );
    }

    public function canToggle(): bool
    {
        return $this->isApiUserSetupComplete() && $this->isConfigAccessActivated();
    }

    /**
     * Check if the API User setup is complete (migration done + password set).
     */
    public function isApiUserSetupComplete(): bool
    {
        try {
            return $this->getApiUserStatusService()->isSetupComplete();
        } catch (\Exception) {
            return false;
        }
    }

    /**
     * Check if Configuration Access module is activated.
     */
    public function isConfigAccessActivated(): bool
    {
        return $this->isModuleActivated(self::CONFIG_ACCESS_MODULE_ID);
    }

    protected function getApiUserStatusService(): ApiUserStatusServiceInterface
    {
        if ($this->apiUserStatusService === null) {
            $this->apiUserStatusService = ContainerFactory::getInstance()
                ->getContainer()
                ->get(ApiUserStatusServiceInterface::class);
        }
        return $this->apiUserStatusService;
    }
}
