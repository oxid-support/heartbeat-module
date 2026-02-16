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
            apiSchemaHash: Module::API_SCHEMA_HASH,
            moduleVersion: Module::VERSION,
            supportedOperations: Module::SUPPORTED_OPERATIONS,
        );
    }
}
