<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidSupport\LoggingFramework\Component\RequestLogger\Service;

use OxidSupport\LoggingFramework\Component\LogSender\DataType\LogPath;
use OxidSupport\LoggingFramework\Component\LogSender\DataType\LogPathType;
use OxidSupport\LoggingFramework\Component\LogSender\Service\LogPathProviderInterface;
use OxidSupport\LoggingFramework\Module\Module;
use OxidSupport\LoggingFramework\Shop\Facade\ModuleSettingFacadeInterface;
use OxidSupport\LoggingFramework\Shop\Facade\ShopFacadeInterface;

/**
 * Provides log paths for the Request Logger component.
 *
 * Tagged with 'oxs.logsender.provider' to be discovered by LogCollectorService.
 */
class LogPathProvider implements LogPathProviderInterface
{
    public function __construct(
        private readonly ShopFacadeInterface $shopFacade,
        private readonly ModuleSettingFacadeInterface $moduleSettingFacade,
    ) {
    }

    public function getLogPaths(): array
    {
        $logDirectory = $this->shopFacade->getLogsPath() . Module::ID . DIRECTORY_SEPARATOR;

        return [
            new LogPath(
                path: $logDirectory,
                type: LogPathType::DIRECTORY,
                name: 'Request Logger Logs',
                description: 'Log files containing recorded shop requests with correlation IDs',
                filePattern: '*.log',
            ),
        ];
    }

    public function getProviderId(): string
    {
        return 'requestlogger';
    }

    public function getProviderName(): string
    {
        return 'Request Logger';
    }

    public function getProviderDescription(): string
    {
        return 'Provides access to request log files that capture user interactions and shop requests.';
    }

    public function isActive(): bool
    {
        return $this->moduleSettingFacade->isRequestLoggerComponentActive();
    }
}
