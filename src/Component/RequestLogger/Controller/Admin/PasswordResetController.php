<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidSupport\Heartbeat\Component\RequestLogger\Controller\Admin;

use OxidEsales\Eshop\Application\Controller\Admin\AdminController;
use OxidEsales\EshopCommunity\Internal\Container\ContainerFactory;
use OxidEsales\EshopCommunity\Internal\Framework\Module\Facade\ModuleSettingServiceInterface;
use OxidSupport\Heartbeat\Module\Module;
use OxidSupport\Heartbeat\Component\ApiUser\Exception\UserNotFoundException;
use OxidSupport\Heartbeat\Component\ApiUser\Service\ApiUserServiceInterface;
use OxidSupport\Heartbeat\Component\ApiUser\Service\TokenGeneratorInterface;

final class PasswordResetController extends AdminController
{
    private ?ApiUserServiceInterface $apiUserService = null;
    private ?ModuleSettingServiceInterface $moduleSettingService = null;
    private ?TokenGeneratorInterface $tokenGenerator = null;

    public function resetPassword(): string
    {
        try {
            $token = $this->getTokenGenerator()->generate();

            $this->getApiUserService()->resetPasswordForApiUser();

            $this->getModuleSettingService()->saveString(
                Module::SETTING_APIUSER_SETUP_TOKEN,
                $token,
                Module::ID
            );

            return 'module_config?oxid=' . Module::ID . '&resetSuccess=1&newToken=' . $token;
        } catch (UserNotFoundException) {
            return 'module_config?oxid=' . Module::ID . '&resetError=USER_NOT_FOUND';
        }
    }

    private function getApiUserService(): ApiUserServiceInterface
    {
        if ($this->apiUserService === null) {
            $this->apiUserService = ContainerFactory::getInstance()
                ->getContainer()
                ->get(ApiUserServiceInterface::class);
        }
        return $this->apiUserService;
    }

    private function getModuleSettingService(): ModuleSettingServiceInterface
    {
        if ($this->moduleSettingService === null) {
            $this->moduleSettingService = ContainerFactory::getInstance()
                ->getContainer()
                ->get(ModuleSettingServiceInterface::class);
        }
        return $this->moduleSettingService;
    }

    private function getTokenGenerator(): TokenGeneratorInterface
    {
        if ($this->tokenGenerator === null) {
            $this->tokenGenerator = ContainerFactory::getInstance()
                ->getContainer()
                ->get(TokenGeneratorInterface::class);
        }
        return $this->tokenGenerator;
    }
}
