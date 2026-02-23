<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidSupport\Heartbeat\Component\ApiVersion\DataType;

use TheCodingMachine\GraphQLite\Annotations\Field;
use TheCodingMachine\GraphQLite\Annotations\Type;

/**
 * GraphQL type for API version information.
 * Enables the heartbeat dashboard to check compatibility with this module.
 */
#[Type]
final class ApiVersionType
{
    /**
     * @param string[] $supportedOperations
     * @param ComponentStatusType[] $componentStatus
     */
    public function __construct(
        private string $apiVersion,
        private string $apiSchemaHash,
        private string $moduleVersion,
        private array $supportedOperations,
        private array $componentStatus = [],
    ) {
    }

    #[Field]
    public function getApiVersion(): string
    {
        return $this->apiVersion;
    }

    #[Field]
    public function getApiSchemaHash(): string
    {
        return $this->apiSchemaHash;
    }

    #[Field]
    public function getModuleVersion(): string
    {
        return $this->moduleVersion;
    }

    /**
     * @return string[]
     */
    #[Field]
    public function getSupportedOperations(): array
    {
        return $this->supportedOperations;
    }

    /**
     * @return ComponentStatusType[]
     */
    #[Field]
    public function getComponentStatus(): array
    {
        return $this->componentStatus;
    }
}
