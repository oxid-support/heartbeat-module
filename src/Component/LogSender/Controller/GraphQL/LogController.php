<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidSupport\LoggingFramework\Component\LogSender\Controller\GraphQL;

use OxidSupport\LoggingFramework\Component\LogSender\DataType\LogContentType;
use OxidSupport\LoggingFramework\Component\LogSender\DataType\LogSourceType;
use OxidSupport\LoggingFramework\Component\LogSender\Service\LogCollectorServiceInterface;
use OxidSupport\LoggingFramework\Component\LogSender\Service\LogReaderServiceInterface;
use OxidSupport\LoggingFramework\Component\LogSender\Service\LogSenderStatusServiceInterface;
use OxidSupport\LoggingFramework\Module\Module;
use OxidEsales\EshopCommunity\Internal\Framework\Module\Facade\ModuleSettingServiceInterface;
use TheCodingMachine\GraphQLite\Annotations\Logged;
use TheCodingMachine\GraphQLite\Annotations\Query;
use TheCodingMachine\GraphQLite\Annotations\Right;

final class LogController
{
    public function __construct(
        private readonly LogCollectorServiceInterface $logCollectorService,
        private readonly LogReaderServiceInterface $logReaderService,
        private readonly LogSenderStatusServiceInterface $statusService,
        private readonly ModuleSettingServiceInterface $moduleSettingService,
    ) {
    }

    /**
     * Get all enabled log sources.
     *
     * @return LogSourceType[]
     */
    #[Query]
    #[Logged]
    #[Right('LOG_SENDER_VIEW')]
    public function logSenderSources(): array
    {
        $this->statusService->assertComponentActive();

        $enabledSourceIds = $this->getEnabledSourceIds();
        $sources = $this->logCollectorService->getSources();

        $result = [];
        foreach ($sources as $source) {
            if (in_array($source->id, $enabledSourceIds, true) && $source->available) {
                $result[] = LogSourceType::fromLogSource($source);
            }
        }

        return $result;
    }

    /**
     * Get content from a specific log source.
     */
    #[Query]
    #[Logged]
    #[Right('LOG_SENDER_VIEW')]
    public function logSenderContent(string $sourceId, ?int $maxBytes = null): LogContentType
    {
        $this->statusService->assertComponentActive();

        $enabledSourceIds = $this->getEnabledSourceIds();
        if (!in_array($sourceId, $enabledSourceIds, true)) {
            throw new \InvalidArgumentException("Source '{$sourceId}' is not enabled for sending.");
        }

        $source = $this->logCollectorService->getSourceById($sourceId);
        if (!$source->available) {
            throw new \InvalidArgumentException("Source '{$sourceId}' is not available.");
        }

        // Use configured max bytes or default
        if ($maxBytes === null) {
            $maxBytes = (int) $this->moduleSettingService->getInteger(
                Module::SETTING_LOGSENDER_MAX_BYTES,
                Module::ID
            );
        }

        // Get the first available path from the source
        $path = null;
        foreach ($source->paths as $logPath) {
            if ($logPath->exists() && $logPath->isReadable() && !$logPath->isDirectory()) {
                $path = $logPath;
                break;
            }
        }

        if ($path === null) {
            throw new \InvalidArgumentException("No readable file found in source '{$sourceId}'.");
        }

        $content = $this->logReaderService->readFile($path->path, $maxBytes);
        $fileInfo = $this->logReaderService->getFileInfo($path->path);
        $truncated = str_starts_with($content, '[...truncated...]');

        return new LogContentType(
            $source->id,
            $source->name,
            $path->path,
            $content,
            $fileInfo['size'],
            $fileInfo['modified'],
            $truncated,
        );
    }

    /**
     * @return string[]
     */
    private function getEnabledSourceIds(): array
    {
        try {
            $sources = $this->moduleSettingService->getCollection(
                Module::SETTING_LOGSENDER_ENABLED_SOURCES,
                Module::ID
            );
            return is_array($sources) ? array_values($sources) : [];
        } catch (\Throwable) {
            return [];
        }
    }
}
