<?php

namespace OxidSupport\Heartbeat\Component\DiagnosticsProvider\Controller\GraphQL;

use OxidSupport\Heartbeat\Component\DiagnosticsProvider\Service\DiagnosticsProvider;
use TheCodingMachine\GraphQLite\Annotations\Query;

final class DiagnosticsGraphQL {
    private DiagnosticsProvider $diagnostics_provider;

    public function __construct(DiagnosticsProvider $diagnosticsProvider)
    {
        $this->diagnostics_provider = $diagnosticsProvider;
    }

    /**
     * @Query()
     */
    #[Query]
    #[Logged]
    public function Diagnostics() : array{
        return $this->diagnostics_provider->getDiagnostics();
    }
}