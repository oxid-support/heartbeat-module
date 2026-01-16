<?php

namespace OxidSupport\Heartbeat\Component\DiagnosticsProvider\Controller\GraphQL;

use OxidSupport\Heartbeat\Component\DiagnosticsProvider\Service\DiagnosticsProvider;
use OxidSupport\Heartbeat\Component\DiagnosticsProvider\Service\DiagnosticsProviderInterface;
use TheCodingMachine\GraphQLite\Annotations\Query;
use TheCodingMachine\GraphQLite\Annotations\Logged;

final class DiagnosticsController {
    private DiagnosticsProviderInterface $diagnostics_provider;

    public function __construct(DiagnosticsProviderInterface $diagnosticsProvider)
    {
        $this->diagnostics_provider = $diagnosticsProvider;
    }

    /**
     * Get Array of Diagnostics - Array
     */
    #[Query]
    #[Logged]
    public function getDiagnostics() : array{
        return $this->diagnostics_provider->getDiagnostics();
    }
}