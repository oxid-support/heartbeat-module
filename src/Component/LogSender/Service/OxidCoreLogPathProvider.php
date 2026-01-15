<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidSupport\LoggingFramework\Component\LogSender\Service;

use OxidSupport\LoggingFramework\Component\LogSender\DataType\LogPath;
use OxidSupport\LoggingFramework\Component\LogSender\DataType\LogPathType;
use OxidSupport\LoggingFramework\Shop\Facade\ShopFacadeInterface;

/**
 * Built-in provider for OXID's core log file (oxideshop.log).
 *
 * This provider is always active and dynamically determines the log path
 * based on the shop's configuration (sShopDir from config.inc.php).
 */
final class OxidCoreLogPathProvider implements LogPathProviderInterface
{
    private const LOG_FILENAME = 'oxideshop.log';

    public function __construct(
        private readonly ShopFacadeInterface $shopFacade,
    ) {
    }

    public function getLogPaths(): array
    {
        $logDirectory = $this->shopFacade->getLogsPath();

        return [
            new LogPath(
                path: $logDirectory . self::LOG_FILENAME,
                type: LogPathType::FILE,
                name: 'OXID eShop Log',
                description: 'Core application log file containing errors, warnings and debug information',
            ),
        ];
    }

    public function getProviderId(): string
    {
        return 'oxid_core';
    }

    public function getProviderName(): string
    {
        return 'OXID Core';
    }

    public function getProviderDescription(): string
    {
        return 'OXID eShop core log file (oxideshop.log)';
    }

    public function isActive(): bool
    {
        // Always active - this is a core log that should always be available
        return true;
    }
}
