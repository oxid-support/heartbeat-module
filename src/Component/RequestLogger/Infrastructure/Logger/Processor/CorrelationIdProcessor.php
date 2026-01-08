<?php

declare(strict_types=1);

namespace OxidSupport\LoggingFramework\Component\RequestLogger\Infrastructure\Logger\Processor;

use OxidSupport\LoggingFramework\Component\RequestLogger\Infrastructure\Logger\CorrelationId\CorrelationIdProviderInterface;

final class CorrelationIdProcessor implements CorrelationIdProcessorInterface
{
    private CorrelationIdProviderInterface $correlationIdProvider;

    public function __construct(CorrelationIdProviderInterface $correlationIdProvider)
    {
        $this->correlationIdProvider = $correlationIdProvider;
    }

    public function __invoke(array $record): array
    {
        $record['context']['correlationId'] = $this->correlationIdProvider->provide();
        return $record;
    }
}
