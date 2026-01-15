<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidSupport\LoggingFramework\Component\LogSender\Service;

use OxidEsales\EshopCommunity\Internal\Framework\Module\Facade\ModuleSettingServiceInterface;
use OxidSupport\LoggingFramework\Component\LogSender\DataType\LogPath;
use OxidSupport\LoggingFramework\Component\LogSender\DataType\LogPathType;
use OxidSupport\LoggingFramework\Component\LogSender\DataType\LogSource;
use OxidSupport\LoggingFramework\Component\LogSender\Exception\LogSourceNotFoundException;
use OxidSupport\LoggingFramework\Module\Module;

/**
 * Service that collects log sources from static paths and DI-tagged providers.
 */
final class LogCollectorService implements LogCollectorServiceInterface
{
    /** @var LogPathProviderInterface[] */
    private array $providers;

    /**
     * @param ModuleSettingServiceInterface $moduleSettingService
     * @param iterable<LogPathProviderInterface> $providers Injected via !tagged_iterator
     */
    public function __construct(
        private readonly ModuleSettingServiceInterface $moduleSettingService,
        iterable $providers
    ) {
        $this->providers = $providers instanceof \Traversable
            ? iterator_to_array($providers)
            : (array) $providers;
    }

    /**
     * @inheritDoc
     */
    public function getSources(): array
    {
        $sources = [];

        // 1. Static paths from settings
        $staticPaths = $this->getStaticPaths();
        foreach ($staticPaths as $index => $path) {
            $sources[] = new LogSource(
                id: 'static_' . $index,
                name: $path->name,
                description: $path->description,
                origin: LogSource::ORIGIN_STATIC,
                providerId: null,
                paths: [$path],
                available: $path->exists(),
            );
        }

        // 2. Provider paths from DI-tagged services
        foreach ($this->providers as $provider) {
            if (!$provider->isActive()) {
                continue;
            }

            $paths = $provider->getLogPaths();
            $allAvailable = $this->checkAllPathsAvailable($paths);

            $sources[] = new LogSource(
                id: 'provider_' . $provider->getProviderId(),
                name: $provider->getProviderName(),
                description: $provider->getProviderDescription(),
                origin: LogSource::ORIGIN_PROVIDER,
                providerId: $provider->getProviderId(),
                paths: $paths,
                available: $allAvailable,
            );
        }

        return $sources;
    }

    /**
     * @inheritDoc
     */
    public function getSourceById(string $sourceId): LogSource
    {
        $sources = $this->getSources();

        foreach ($sources as $source) {
            if ($source->id === $sourceId) {
                return $source;
            }
        }

        throw new LogSourceNotFoundException($sourceId);
    }

    /**
     * @inheritDoc
     */
    public function getStaticPaths(): array
    {
        try {
            $configured = $this->moduleSettingService->getCollection(
                Module::SETTING_LOGSENDER_STATIC_PATHS,
                Module::ID
            );
        } catch (\Throwable) {
            // Setting not configured yet
            return [];
        }

        $paths = [];
        foreach ($configured as $config) {
            if (!is_array($config) || !isset($config['path'], $config['type'])) {
                continue;
            }

            $type = LogPathType::tryFrom($config['type']);
            if ($type === null) {
                continue;
            }

            $paths[] = new LogPath(
                path: $config['path'],
                type: $type,
                name: $config['name'] ?? basename($config['path']),
                description: $config['description'] ?? '',
                filePattern: $config['pattern'] ?? null,
            );
        }

        return $paths;
    }

    /**
     * Check if all paths in the array are available.
     *
     * @param LogPath[] $paths
     */
    private function checkAllPathsAvailable(array $paths): bool
    {
        if (empty($paths)) {
            return false;
        }

        foreach ($paths as $path) {
            if (!$path->exists()) {
                return false;
            }
        }

        return true;
    }
}
