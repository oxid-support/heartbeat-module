<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidSupport\LoggingFramework\Component\ApiUser\Controller\Admin;

use OxidEsales\Eshop\Application\Controller\Admin\AdminController;
use OxidEsales\EshopCommunity\Internal\Container\ContainerFactory;
use OxidEsales\EshopCommunity\Internal\Framework\Module\Configuration\Dao\ShopConfigurationDaoInterface;
use OxidEsales\EshopCommunity\Internal\Framework\Module\Facade\ModuleSettingServiceInterface;
use OxidEsales\EshopCommunity\Internal\Transition\Utility\ContextInterface;
use OxidSupport\LoggingFramework\Module\Module;
use OxidSupport\LoggingFramework\Component\ApiUser\Service\ApiUserServiceInterface;
use OxidSupport\LoggingFramework\Component\ApiUser\Service\ApiUserStatusServiceInterface;

/**
 * API User setup controller for the Logging Framework.
 * Displays the setup workflow for API user configuration.
 */
class SetupController extends AdminController
{
    protected $_sThisTemplate = '@oxsloggingframework/admin/loggingframework_apiuser_setup';

    private const GRAPHQL_BASE_MODULE_ID = 'oe_graphql_base';

    private ?ContextInterface $context = null;
    private ?ShopConfigurationDaoInterface $shopConfigurationDao = null;
    private ?ApiUserServiceInterface $apiUserService = null;
    private ?ApiUserStatusServiceInterface $apiUserStatusService = null;
    private ?ModuleSettingServiceInterface $moduleSettingService = null;

    /**
     * Get the setup token.
     */
    public function getSetupToken(): string
    {
        return (string) $this->getModuleSettingService()->getString(
            Module::SETTING_APIUSER_SETUP_TOKEN,
            Module::ID
        );
    }

    /**
     * Check if the module is activated.
     */
    public function isModuleActivated(): bool
    {
        return $this->isModuleActive(Module::ID);
    }

    /**
     * Check if GraphQL Base module is activated.
     */
    public function isGraphqlBaseActivated(): bool
    {
        return $this->isModuleActive(self::GRAPHQL_BASE_MODULE_ID);
    }

    /**
     * Check if migrations have been executed.
     */
    public function isMigrationExecuted(): bool
    {
        try {
            return $this->getApiUserStatusService()->isMigrationExecuted();
        } catch (\Exception) {
            return false;
        }
    }

    /**
     * Check if the API user exists.
     */
    public function isApiUserCreated(): bool
    {
        try {
            return $this->getApiUserStatusService()->isApiUserCreated();
        } catch (\Exception) {
            return false;
        }
    }

    /**
     * Check if the API user password is set (setup complete).
     */
    public function isApiUserPasswordSet(): bool
    {
        try {
            return $this->getApiUserStatusService()->isApiUserPasswordSet();
        } catch (\Exception) {
            return false;
        }
    }

    /**
     * Check if the complete setup is done.
     */
    public function isSetupComplete(): bool
    {
        try {
            return $this->getApiUserStatusService()->isSetupComplete();
        } catch (\Exception) {
            return false;
        }
    }

    /**
     * Check if a specific module is activated.
     */
    private function isModuleActive(string $moduleId): bool
    {
        try {
            $shopConfiguration = $this->getShopConfigurationDao()->get(
                $this->getContext()->getCurrentShopId()
            );
            return $shopConfiguration
                ->getModuleConfiguration($moduleId)
                ->isActivated();
        } catch (\Exception) {
            return false;
        }
    }

    /**
     * Reset the API user password (regenerate setup token).
     */
    public function resetPassword(): void
    {
        // Generate a new setup token
        $newToken = bin2hex(random_bytes(32));

        // Reset the API user password to placeholder
        try {
            $this->getApiUserService()->resetPasswordForApiUser();
        } catch (\Exception) {
            // User might not exist yet, ignore
        }

        $this->getModuleSettingService()->saveString(
            Module::SETTING_APIUSER_SETUP_TOKEN,
            $newToken,
            Module::ID
        );
    }

    protected function getContext(): ContextInterface
    {
        if ($this->context === null) {
            $this->context = ContainerFactory::getInstance()
                ->getContainer()
                ->get(ContextInterface::class);
        }
        return $this->context;
    }

    protected function getShopConfigurationDao(): ShopConfigurationDaoInterface
    {
        if ($this->shopConfigurationDao === null) {
            $this->shopConfigurationDao = ContainerFactory::getInstance()
                ->getContainer()
                ->get(ShopConfigurationDaoInterface::class);
        }
        return $this->shopConfigurationDao;
    }

    protected function getApiUserService(): ApiUserServiceInterface
    {
        if ($this->apiUserService === null) {
            $this->apiUserService = ContainerFactory::getInstance()
                ->getContainer()
                ->get(ApiUserServiceInterface::class);
        }
        return $this->apiUserService;
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

    protected function getModuleSettingService(): ModuleSettingServiceInterface
    {
        if ($this->moduleSettingService === null) {
            $this->moduleSettingService = ContainerFactory::getInstance()
                ->getContainer()
                ->get(ModuleSettingServiceInterface::class);
        }
        return $this->moduleSettingService;
    }
}
