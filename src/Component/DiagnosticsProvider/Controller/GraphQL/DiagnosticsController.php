<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidSupport\Heartbeat\Component\DiagnosticsProvider\Controller\GraphQL;

use OxidSupport\Heartbeat\Component\DiagnosticsProvider\DataType\DiagnosticsType;
use OxidSupport\Heartbeat\Component\DiagnosticsProvider\Service\DiagnosticsProviderInterface;
use TheCodingMachine\GraphQLite\Annotations\Logged;
use TheCodingMachine\GraphQLite\Annotations\Query;
use TheCodingMachine\GraphQLite\Annotations\Right;

final class DiagnosticsController
{
    public function __construct(
        private readonly DiagnosticsProviderInterface $diagnosticsProvider
    ) {
    }

    /**
     * Get comprehensive diagnostics information about the shop
     */
    #[Query]
    #[Logged]
    #[Right('LOG_SENDER_VIEW')]
    public function getDiagnostics(): DiagnosticsType
    {
        $diagnosticsArray = $this->diagnosticsProvider->getDiagnostics();
        return DiagnosticsType::fromDiagnosticsArray($diagnosticsArray);
    }

    /**
     * Test query to verify GraphQL endpoint is working
     */
    #[Query]
    public function getTestDiagnostics(): string
    {
        return "success";
    }
}