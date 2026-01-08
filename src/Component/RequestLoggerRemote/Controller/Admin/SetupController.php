<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidSupport\LoggingFramework\Component\RequestLoggerRemote\Controller\Admin;

use OxidEsales\Eshop\Application\Controller\Admin\AdminController;
use OxidEsales\EshopCommunity\Internal\Container\ContainerFactory;
use OxidEsales\EshopCommunity\Internal\Framework\Module\Configuration\Dao\ShopConfigurationDaoInterface;
use OxidEsales\EshopCommunity\Internal\Framework\Module\Facade\ModuleSettingServiceInterface;
use OxidEsales\EshopCommunity\Internal\Transition\Utility\ContextInterface;
use OxidSupport\LoggingFramework\Module\Module;
use OxidSupport\LoggingFramework\Component\RequestLoggerRemote\Service\SetupStatusServiceInterface;

/**
 * Request Logger Remote setup controller for the Logging Framework.
 * Displays the setup workflow for remote access configuration.
 */
class SetupController extends AdminController
{
    protected $_sThisTemplate = '@oxsloggingframework/admin/loggingframework_remote_setup';

    private const GRAPHQL_BASE_MODULE_ID = 'oe_graphql_base';
    private const CONFIG_ACCESS_MODULE_ID = 'oe_graphql_configuration_access';
    private const SETTING_COMPONENT_ACTIVE = Module::SETTING_REMOTE_ACTIVE;

    private ?ContextInterface $context = null;
    private ?ShopConfigurationDaoInterface $shopConfigurationDao = null;
    private ?SetupStatusServiceInterface $setupStatusService = null;
    private ?ModuleSettingServiceInterface $moduleSettingService = null;

    /**
     * Check if the component is active.
     */
    public function isComponentActive(): bool
    {
        return $this->getModuleSettingService()->getBoolean(self::SETTING_COMPONENT_ACTIVE, Module::ID);
    }

    /**
     * Toggle component activation.
     */
    public function toggleComponent(): void
    {
        $currentState = $this->isComponentActive();
        $this->getModuleSettingService()->saveBoolean(
            self::SETTING_COMPONENT_ACTIVE,
            !$currentState,
            Module::ID
        );
    }

    /**
     * Get the setup token.
     */
    public function getSetupToken(): string
    {
        return (string) $this->getModuleSettingService()->getString(
            Module::SETTING_REMOTE_SETUP_TOKEN,
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
     * Check if Configuration Access module is activated.
     */
    public function isConfigAccessActivated(): bool
    {
        return $this->isModuleActive(self::CONFIG_ACCESS_MODULE_ID);
    }

    /**
     * Check if migrations have been executed.
     */
    public function isMigrationExecuted(): bool
    {
        try {
            return $this->getSetupStatusService()->isMigrationExecuted();
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

        $this->getModuleSettingService()->saveString(
            Module::SETTING_REMOTE_SETUP_TOKEN,
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

    protected function getSetupStatusService(): SetupStatusServiceInterface
    {
        if ($this->setupStatusService === null) {
            $this->setupStatusService = ContainerFactory::getInstance()
                ->getContainer()
                ->get(SetupStatusServiceInterface::class);
        }
        return $this->setupStatusService;
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
