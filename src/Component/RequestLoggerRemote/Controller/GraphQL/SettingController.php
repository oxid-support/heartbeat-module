<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidSupport\LoggingFramework\Component\RequestLoggerRemote\Controller\GraphQL;

use OxidSupport\LoggingFramework\Component\RequestLoggerRemote\DataType\SettingType;
use OxidSupport\LoggingFramework\Component\RequestLoggerRemote\Service\RemoteComponentStatusServiceInterface;
use OxidSupport\LoggingFramework\Component\RequestLoggerRemote\Service\SettingServiceInterface;
use TheCodingMachine\GraphQLite\Annotations\Logged;
use TheCodingMachine\GraphQLite\Annotations\Mutation;
use TheCodingMachine\GraphQLite\Annotations\Query;
use TheCodingMachine\GraphQLite\Annotations\Right;

final class SettingController
{
    public function __construct(
        private SettingServiceInterface $settingService,
        private RemoteComponentStatusServiceInterface $componentStatusService
    ) {
    }

    /**
     * Get all request logger settings with their types
     *
     * @return SettingType[]
     */
    #[Query]
    #[Logged]
    #[Right('REQUEST_LOGGER_VIEW')]
    public function requestLoggerSettings(): array
    {
        $this->componentStatusService->assertComponentActive();
        return $this->settingService->getAllSettings();
    }

    /**
     * Get the current log level setting
     */
    #[Query]
    #[Logged]
    #[Right('REQUEST_LOGGER_VIEW')]
    public function requestLoggerLogLevel(): string
    {
        $this->componentStatusService->assertComponentActive();
        return $this->settingService->getLogLevel();
    }

    /**
     * Get the log-frontend enabled setting
     */
    #[Query]
    #[Logged]
    #[Right('REQUEST_LOGGER_VIEW')]
    public function requestLoggerLogFrontend(): bool
    {
        $this->componentStatusService->assertComponentActive();
        return $this->settingService->isLogFrontendEnabled();
    }

    /**
     * Get the log-admin enabled setting
     */
    #[Query]
    #[Logged]
    #[Right('REQUEST_LOGGER_VIEW')]
    public function requestLoggerLogAdmin(): bool
    {
        $this->componentStatusService->assertComponentActive();
        return $this->settingService->isLogAdminEnabled();
    }

    /**
     * Get the redact items collection (JSON-encoded array)
     */
    #[Query]
    #[Logged]
    #[Right('REQUEST_LOGGER_VIEW')]
    public function requestLoggerRedact(): string
    {
        $this->componentStatusService->assertComponentActive();
        return $this->settingService->getRedactItems();
    }

    /**
     * Get the redact-all-values enabled setting
     */
    #[Query]
    #[Logged]
    #[Right('REQUEST_LOGGER_VIEW')]
    public function requestLoggerRedactAllValues(): bool
    {
        $this->componentStatusService->assertComponentActive();
        return $this->settingService->isRedactAllValuesEnabled();
    }

    /**
     * Change the log level setting
     */
    #[Mutation]
    #[Logged]
    #[Right('REQUEST_LOGGER_CHANGE')]
    public function requestLoggerLogLevelChange(string $value): string
    {
        $this->componentStatusService->assertComponentActive();
        return $this->settingService->setLogLevel($value);
    }

    /**
     * Change the log-frontend enabled setting
     */
    #[Mutation]
    #[Logged]
    #[Right('REQUEST_LOGGER_CHANGE')]
    public function requestLoggerLogFrontendChange(bool $value): bool
    {
        $this->componentStatusService->assertComponentActive();
        return $this->settingService->setLogFrontendEnabled($value);
    }

    /**
     * Change the log-admin enabled setting
     */
    #[Mutation]
    #[Logged]
    #[Right('REQUEST_LOGGER_CHANGE')]
    public function requestLoggerLogAdminChange(bool $value): bool
    {
        $this->componentStatusService->assertComponentActive();
        return $this->settingService->setLogAdminEnabled($value);
    }

    /**
     * Change the redact items collection (expects JSON array string)
     */
    #[Mutation]
    #[Logged]
    #[Right('REQUEST_LOGGER_CHANGE')]
    public function requestLoggerRedactChange(string $value): string
    {
        $this->componentStatusService->assertComponentActive();
        return $this->settingService->setRedactItems($value);
    }

    /**
     * Change the redact-all-values enabled setting
     */
    #[Mutation]
    #[Logged]
    #[Right('REQUEST_LOGGER_CHANGE')]
    public function requestLoggerRedactAllValuesChange(bool $value): bool
    {
        $this->componentStatusService->assertComponentActive();
        return $this->settingService->setRedactAllValuesEnabled($value);
    }
}
