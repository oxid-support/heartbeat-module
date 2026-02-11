<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidSupport\Heartbeat\Component\RequestLogger\Controller\Admin;

use OxidEsales\EshopCommunity\Internal\Container\ContainerFactory;
use OxidSupport\Heartbeat\Module\Module;
use OxidSupport\Heartbeat\Component\ApiUser\Service\ApiUserStatusServiceInterface;
use OxidSupport\Heartbeat\Shared\Controller\Admin\AbstractComponentController;
use OxidSupport\Heartbeat\Shared\Controller\Admin\TogglableComponentInterface;

class RemoteSetupController extends AbstractComponentController implements TogglableComponentInterface
{
    protected $_sThisTemplate = '@oxsheartbeat/admin/heartbeat_requestlogger_setup';

    private const CONFIG_ACCESS_MODULE_ID = 'oe_graphql_configuration_access';

    private ?ApiUserStatusServiceInterface $apiUserStatusService = null;

    public function isComponentActive(): bool
    {
        return $this->getModuleSettingService()->getBoolean(
            Module::SETTING_REQUESTLOGGER_ACTIVE,
            Module::ID
        );
    }

    public function getStatusClass(): string
    {
        if (!$this->isApiUserSetupComplete()) {
            return self::STATUS_CLASS_WARNING;
        }
        return parent::getStatusClass();
    }

    public function getStatusTextKey(): string
    {
        if (!$this->isApiUserSetupComplete()) {
            return 'OXSHEARTBEAT_REQUESTLOGGER_REMOTE_STATUS_WARNING';
        }
        return parent::getStatusTextKey();
    }

    public function toggleComponent(): void
    {
        if (!$this->canToggle()) {
            return;
        }

        $this->getModuleSettingService()->saveBoolean(
            Module::SETTING_REQUESTLOGGER_ACTIVE,
            !$this->isComponentActive(),
            Module::ID
        );
    }

    public function canToggle(): bool
    {
        return $this->isApiUserSetupComplete() && $this->isConfigAccessActivated();
    }

    public function isApiUserSetupComplete(): bool
    {
        try {
            return $this->getApiUserStatusService()->isSetupComplete();
        } catch (\Exception) {
            return false;
        }
    }

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
        return $this->apiUserStatusService; // @phpstan-ignore return.type
    }
}
