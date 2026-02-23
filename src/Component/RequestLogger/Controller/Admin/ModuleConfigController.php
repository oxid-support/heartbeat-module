<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidSupport\Heartbeat\Component\RequestLogger\Controller\Admin;

use OxidEsales\Eshop\Application\Controller\Admin\ModuleConfiguration;
use OxidEsales\EshopCommunity\Internal\Container\ContainerFactory;
use OxidSupport\Heartbeat\Module\Module;
use OxidSupport\Heartbeat\Component\RequestLogger\Service\Remote\SetupStatusServiceInterface;

class ModuleConfigController extends ModuleConfiguration
{
    private ?SetupStatusServiceInterface $setupStatusService = null;

    private const GRAPHQL_BASE_MODULE_ID = 'oe_graphql_base';
    private const CONFIG_ACCESS_MODULE_ID = 'oe_graphql_configuration_access';

    public function isModuleActivated(): bool
    {
        if ($this->getCurrentModuleId() !== Module::ID) {
            return false;
        }

        return $this->isModuleActive(Module::ID);
    }

    public function isGraphqlBaseActivated(): bool
    {
        return $this->isModuleActive(self::GRAPHQL_BASE_MODULE_ID);
    }

    public function isConfigAccessActivated(): bool
    {
        return $this->isModuleActive(self::CONFIG_ACCESS_MODULE_ID);
    }

    private function isModuleActive(string $moduleId): bool
    {
        try {
            /** @var \OxidEsales\Eshop\Core\Module\Module $module */
            $module = oxNew(\OxidEsales\Eshop\Core\Module\Module::class);
            $module->load($moduleId);
            return $module->isActive();
        } catch (\Exception) {
            return false;
        }
    }

    public function isMigrationExecuted(): bool
    {
        if ($this->getCurrentModuleId() !== Module::ID) {
            return true;
        }

        try {
            return $this->getSetupStatusService()->isMigrationExecuted();
        } catch (\Exception) {
            return false;
        }
    }

    protected function getCurrentModuleId(): string
    {
        return $this->getEditObjectId();
    }

    private function getSetupStatusService(): SetupStatusServiceInterface
    {
        if ($this->setupStatusService === null) {
            $this->setupStatusService = ContainerFactory::getInstance()
                ->getContainer()
                ->get(SetupStatusServiceInterface::class);
        }
        return $this->setupStatusService; // @phpstan-ignore return.type
    }
}
