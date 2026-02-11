<?php

declare(strict_types=1);

namespace OxidSupport\Heartbeat\Component\RequestLogger\Infrastructure\Logger\Processor;

use OxidSupport\Heartbeat\Component\RequestLogger\Infrastructure\Logger\CorrelationId\CorrelationIdProviderInterface;

final class CorrelationIdProcessor implements CorrelationIdProcessorInterface
{
    private CorrelationIdProviderInterface $correlationIdProvider;

    public function __construct(CorrelationIdProviderInterface $correlationIdProvider)
    {
        $this->correlationIdProvider = $correlationIdProvider;
    }

    /** @param array<string, mixed> $record @return array<string, mixed> */
    public function __invoke(array $record): array
    {
        $record['context']['correlationId'] = $this->correlationIdProvider->provide();
        return $record;
    }
}
