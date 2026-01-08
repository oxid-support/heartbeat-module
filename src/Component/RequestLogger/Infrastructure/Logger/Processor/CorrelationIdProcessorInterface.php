<?php

declare(strict_types=1);

namespace OxidSupport\LoggingFramework\Component\RequestLogger\Infrastructure\Logger\Processor;

interface CorrelationIdProcessorInterface
{
    public function __invoke(array $record): array;
}
