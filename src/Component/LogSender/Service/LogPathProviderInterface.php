<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidSupport\LoggingFramework\Component\LogSender\Service;

use OxidSupport\LoggingFramework\Component\LogSender\DataType\LogPath;

/**
 * Interface for services that provide log paths.
 *
 * Implementing classes must be tagged with 'oxs.logsender.provider'
 * in the DI container to be discovered by the LogCollectorService.
 *
 * Example services.yaml:
 * ```yaml
 * MyLogPathProvider:
 *     tags:
 *         - { name: 'oxs.logsender.provider' }
 * ```
 */
interface LogPathProviderInterface
{
    /**
     * Returns the log paths that this provider offers.
     *
     * @return LogPath[]
     */
    public function getLogPaths(): array;

    /**
     * Returns a unique identifier for this provider.
     * Used to distinguish sources from different providers.
     *
     * Example: 'requestlogger', 'oxidcore', 'mymodule'
     */
    public function getProviderId(): string;

    /**
     * Returns a human-readable name for this provider.
     *
     * Example: 'Request Logger', 'OXID Core Logs'
     */
    public function getProviderName(): string;

    /**
     * Returns a description of what logs this provider offers.
     */
    public function getProviderDescription(): string;

    /**
     * Indicates whether this provider is currently active.
     *
     * For example, the Request Logger provider should only be active
     * when the Request Logger component is enabled.
     */
    public function isActive(): bool;
}
