<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidSupport\Heartbeat\Component\ApiVersion\Controller\GraphQL;

use OxidSupport\Heartbeat\Component\ApiVersion\DataType\ApiVersionType;
use OxidSupport\Heartbeat\Component\ApiVersion\Service\ApiVersionServiceInterface;
use TheCodingMachine\GraphQLite\Annotations\Query;

final class ApiVersionController
{
    public function __construct(
        private readonly ApiVersionServiceInterface $apiVersionService,
    ) {
    }

    /**
     * Returns API version information for compatibility checks.
     * No authentication required - this is a discovery/health endpoint.
     */
    #[Query]
    public function heartbeatApiVersion(): ApiVersionType
    {
        return $this->apiVersionService->getApiVersion();
    }
}
