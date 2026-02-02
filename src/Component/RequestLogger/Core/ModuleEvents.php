<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidSupport\Heartbeat\Component\RequestLogger\Core;

use OxidEsales\Eshop\Core\Registry;
use OxidEsales\EshopCommunity\Internal\Container\ContainerFactory;
use OxidEsales\EshopCommunity\Internal\Framework\Database\QueryBuilderFactoryInterface;
use OxidEsales\EshopCommunity\Internal\Framework\Module\Facade\ModuleSettingServiceInterface;
use OxidSupport\Heartbeat\Module\Module;

final class ModuleEvents
{
    /**
     * Called on module activation.
     * Generates a setup token only if:
     * - Token doesn't exist yet, AND
     * - Password is not yet set (still placeholder)
     *
     * This prevents generating a new token when reactivating after successful setup.
     */
    public static function onActivate(): void
    {
        $container = ContainerFactory::getInstance()->getContainer();
        $moduleSettingService = $container->get(ModuleSettingServiceInterface::class);

        try {
            $currentToken = (string) $moduleSettingService->getString(Module::SETTING_APIUSER_SETUP_TOKEN, Module::ID);
        } catch (\Throwable $e) {
            $currentToken = '';
        }

        if (!empty($currentToken)) {
            return;
        }

        if (self::isPasswordAlreadySet($container)) {
            return;
        }

        $token = Registry::getUtilsObject()->generateUId();
        $moduleSettingService->saveString(Module::SETTING_APIUSER_SETUP_TOKEN, $token, Module::ID);
    }

    private static function isPasswordAlreadySet($container): bool
    {
        try {
            $queryBuilderFactory = $container->get(QueryBuilderFactoryInterface::class);
            $queryBuilder = $queryBuilderFactory->create();
            $queryBuilder
                ->select('OXPASSWORD')
                ->from('oxuser')
                ->where('OXUSERNAME = :email')
                ->setParameter('email', Module::API_USER_EMAIL);

            $password = $queryBuilder->execute()->fetchOne();

            return $password && str_starts_with($password, '$');
        } catch (\Throwable $e) {
            return false;
        }
    }
}
