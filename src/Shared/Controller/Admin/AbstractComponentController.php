<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidSupport\LoggingFramework\Shared\Controller\Admin;

use OxidEsales\Eshop\Application\Controller\Admin\AdminController;
use OxidEsales\EshopCommunity\Internal\Container\ContainerFactory;
use OxidEsales\EshopCommunity\Internal\Framework\Module\Configuration\Dao\ShopConfigurationDaoInterface;
use OxidEsales\EshopCommunity\Internal\Framework\Module\Facade\ModuleSettingServiceInterface;
use OxidEsales\EshopCommunity\Internal\Transition\Utility\ContextInterface;

/**
 * Abstract base controller for Logging Framework component pages.
 *
 * Provides common functionality:
 * - Module setting access
 * - Module activation checks
 * - Status determination for consistent UI
 */
abstract class AbstractComponentController extends AdminController implements ComponentControllerInterface
{
    private ?ContextInterface $context = null;
    private ?ShopConfigurationDaoInterface $shopConfigurationDao = null;
    private ?ModuleSettingServiceInterface $moduleSettingService = null;

    /**
     * Default implementation: active/inactive based on isComponentActive().
     *
     * Override in subclasses for custom logic (e.g., warning state).
     */
    public function getStatusClass(): string
    {
        return $this->isComponentActive()
            ? self::STATUS_CLASS_ACTIVE
            : self::STATUS_CLASS_INACTIVE;
    }

    /**
     * Default implementation: returns standard active/inactive translation keys.
     *
     * Override in subclasses for component-specific status text.
     */
    public function getStatusTextKey(): string
    {
        return $this->isComponentActive()
            ? 'OXSREQUESTLOGGER_LF_STATUS_ACTIVE'
            : 'OXSREQUESTLOGGER_LF_STATUS_INACTIVE';
    }

    /**
     * Check if a specific module is activated in the shop.
     */
    protected function isModuleActivated(string $moduleId): bool
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
