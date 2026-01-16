<?php

namespace OxidSupport\LoggingFramework\Component\DiagnosticsProvider\Controller\GraphQL;

use OxidSupport\LoggingFramework\Component\DiagnosticsProvider\Service\DiagnosticsProvider;
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
    public function Diagnostics() : array{
        $this->diagnostics_provider->getDiagnostics();
    }
}