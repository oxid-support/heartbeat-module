<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidSupport\Heartbeat\Component\ApiVersion\Service;

use OxidSupport\Heartbeat\Component\ApiVersion\DataType\ApiVersionType;
use OxidSupport\Heartbeat\Module\Module;

final class ApiVersionService implements ApiVersionServiceInterface
{
    public function getApiVersion(): ApiVersionType
    {
        return new ApiVersionType(
            apiVersion: Module::API_VERSION,
            apiSchemaHash: self::computeSchemaHash(Module::SUPPORTED_OPERATIONS),
            moduleVersion: Module::VERSION,
            supportedOperations: Module::SUPPORTED_OPERATIONS,
        );
    }

    /**
     * Compute a schema hash from the list of supported operations.
     * Algorithm: sort operations alphabetically, join with newline, SHA-256, take first 16 hex chars.
     *
     * @param string[] $operations
     */
    public static function computeSchemaHash(array $operations): string
    {
        $sorted = $operations;
        sort($sorted);

        return substr(hash('sha256', implode("\n", $sorted)), 0, 16);
    }
}
